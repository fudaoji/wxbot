layui.define(['layer', 'upload', 'flow'], function(exports) {
    var $ = layui.jquery;
    var layer = layui.layer;
    var upload = layui.upload;
    var flow = layui.flow;

    // 存储类型枚举
    var STORAGE_TYPE = {
        ALIYUN: 'aliyun',
        QINIU: 'qiniu',
        TENCENT: 'tencent'
    };

    // 文件类型枚举
    var FILE_TYPE = {
        IMAGE: 'image',
        VIDEO: 'video',
        FILE: 'file'
    };

    // 存储SDK加载状态
    var sdkLoaded = {
        aliyun: false,
        qiniu: false,
        tencent: false
    };

    // 存储SDK加载Promise
    var sdkPromises = {
        aliyun: null,
        qiniu: null,
        tencent: null
    };

    // 加载SDK文件
    function loadSdk(storageType, retryCount = 3) {
        if (sdkLoaded[storageType]) {
            return Promise.resolve();
        }

        if (sdkPromises[storageType]) {
            return sdkPromises[storageType];
        }

        var scriptUrl;

        switch(storageType) {
            case STORAGE_TYPE.ALIYUN:
                scriptUrl = 'https://gosspublic.alicdn.com/aliyun-oss-sdk-6.17.0.min.js';
                break;

            case STORAGE_TYPE.QINIU:
                scriptUrl = 'https://cdn.jsdelivr.net/npm/qiniu-js@3.0/dist/qiniu.min.js';
                break;

            case STORAGE_TYPE.TENCENT:
                scriptUrl = 'https://cdn.jsdelivr.net/npm/cos-js-sdk-v5@1.4.4/dist/cos-js-sdk-v5.min.js';
                break;
        }

        sdkPromises[storageType] = new Promise(function(resolve, reject) {
            var script = document.createElement('script');
            script.src = scriptUrl;

            script.onload = function() {
                sdkLoaded[storageType] = true;
                resolve();
            };

            script.onerror = function() {
                if (retryCount > 0) {
                    // 重试机制，延迟1秒后重试
                    setTimeout(() => {
                        loadSdk(storageType, retryCount - 1).then(resolve).catch(reject);
                    }, 1000);
                } else {
                    reject(new Error(`Failed to load ${storageType} SDK after ${3} attempts`));
                }
            };

            document.head.appendChild(script);
        });

        return sdkPromises[storageType];
    }

    // 上传组件构造函数
    var UploadMulti = function(options) {
        this.options = $.extend(true, {
            elem: null,                 // 绑定元素
            storageType: STORAGE_TYPE.ALIYUN, // 默认存储类型
            fileType: FILE_TYPE.FILE,   // 文件类型
            maxCount: 10,               // 最大上传数量
            maxSize: 10,                // 最大文件大小(MB)
            accept: 'file',             // 接受的文件类型
            acceptExts: '',             // 允许的文件扩展名，多个用逗号分隔
            savePath: '',               // 保存的目录
            multiple: true,             // 是否允许多文件
            autoUpload: true,           // 是否自动上传
            uploadText: '点击上传',     // 上传按钮文本
            initFiles: [],              // 初始化文件数据
            videoThumbnail: true,       // 启用视频缩略图生成
            onSuccess: function(file, res) {}, // 上传成功回调
            onError: function(file, error) {}, // 上传失败回调
            onProgress: function(file, percent) {}, // 上传进度回调
            onChange: function(files) {} // 文件列表变化回调
        }, options);

        this.init();
    };

    // 初始化方法
    UploadMulti.prototype.init = function() {
        var that = this;
        var options = this.options;

        // 创建上传容器
        this.container = $(options.elem);
        this.createUploadBox();

        // 渲染初始化文件
        this.renderInitFiles();

        // 初始化上传实例
        this.uploadInst = upload.render({
            elem: this.uploadBtn[0],
            url: 'about:blank', // 禁用默认上传
            accept: options.accept,
            acceptMime: options.acceptMime,
            multiple: options.multiple,
            size: options.maxSize * 1024, // MB转KB
            auto: false,
            number: options.maxCount - this.options.initFiles.length,
            choose: function(obj) {
                var files = obj.pushFile(); // 获取选择的文件
                that.handleFiles(obj, files);
            }
        });

        // 加载存储SDK并初始化客户端
        this.loadStorageSdk().then(function() {
            that.initStorageClient();
        }).catch(function(error) {
            layer.msg(`加载存储SDK失败: ${error.message}`, {icon: 2});
            console.error('加载存储SDK失败:', error);
        });
    };

    // 渲染初始化文件
    UploadMulti.prototype.renderInitFiles = function() {
        var that = this;
        var options = this.options;

        options.initFiles.forEach(function(file) {
            var fileItem = that.createFileItem(file, file.url, true);
            that.uploadList.append(fileItem);

            // 如果是视频且启用了缩略图功能，生成缩略图
            if (options.fileType === FILE_TYPE.VIDEO && options.videoThumbnail) {
                that.generateVideoThumbnail(fileItem, file, file.url);
            }
        });

        // 更新文件列表
        this.updateFileList();
    };

    // 获取文件类型类
    UploadMulti.prototype.getFileTypeClass = function(ext) {
        switch(ext) {
            case 'pdf': return 'file-type-pdf';
            case 'doc':
            case 'docx': return 'file-type-doc';
            case 'xls':
            case 'xlsx': return 'file-type-xls';
            case 'ppt':
            case 'pptx': return 'file-type-ppt';
            case 'txt': return 'file-type-txt';
            case 'zip':
            case 'rar':
            case '7z': return 'file-type-zip';
            default: return 'file-type-unknown';
        }
    };

    // 获取文件图标
    UploadMulti.prototype.getFileIcon = function(ext) {
        switch(ext) {
            case 'pdf': return 'fa-file-pdf-o';
            case 'doc':
            case 'docx': return 'fa-file-word-o';
            case 'xls':
            case 'xlsx': return 'fa-file-excel-o';
            case 'ppt':
            case 'pptx': return 'fa-file-powerpoint-o';
            case 'txt': return 'fa-file-text-o';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif': return 'fa-file-image-o';
            case 'mp4':
            case 'mov':
            case 'avi': return 'fa-file-video-o';
            case 'mp3':
            case 'wav': return 'fa-file-audio-o';
            case 'zip':
            case 'rar':
            case '7z': return 'fa-file-archive-o';
            default: return 'fa-file-o';
        }
    };

    // 加载存储SDK
    UploadMulti.prototype.loadStorageSdk = function() {
        var that = this;
        var options = this.options;

        // 显示加载状态
        this.showLoading('正在加载存储SDK...');

        return loadSdk(options.storageType).then(function() {
            that.hideLoading();
        });
    };

    // 创建上传容器
    UploadMulti.prototype.createUploadBox = function() {
        var options = this.options;

        this.container.html(`
          <div class="layui-upload-box">
            <div class="layui-upload-loading layui-hide">
              <i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i>
            </div>
            <div class="layui-upload-list" id="uploadList"></div>
            <div class="layui-upload-btn" id="uploadBtn">
              <i class="layui-icon layui-icon-upload-drag"></i>
              <div>${options.uploadText}</div>
            </div>
          </div>
        `);

        this.uploadList = this.container.find('#uploadList');
        this.uploadBtn = this.container.find('#uploadBtn');
        this.loading = this.container.find('.layui-upload-loading');
    };

    // 显示加载状态
    UploadMulti.prototype.showLoading = function(text) {
        this.loading.find('i').next().remove();
        if (text) {
            this.loading.find('i').after(`<div class="layui-mt5">${text}</div>`);
        }
        this.loading.removeClass('layui-hide');
    };

    // 隐藏加载状态
    UploadMulti.prototype.hideLoading = function() {
        this.loading.addClass('layui-hide');
    };

    // 初始化存储客户端
    UploadMulti.prototype.initStorageClient = function() {
        var options = this.options;

        switch(options.storageType) {
            case STORAGE_TYPE.ALIYUN:
                this.storageClient = new OSS({
                    region: options.aliyun.region,
                    accessKeyId: options.aliyun.accessKeyId,
                    accessKeySecret: options.aliyun.accessKeySecret,
                    bucket: options.aliyun.bucket
                });
                break;

            case STORAGE_TYPE.QINIU:
                this.storageClient = {
                    upload: function(file, key, token, putExtra, config) {
                        var observer = {
                            next: function() {},
                            error: function() {},
                            complete: function() {}
                        };

                        var subscription = {
                            subscribe: function(obs) {
                                observer = obs;

                                var formUploader = new qiniu.form_up.FormUploader(config);
                                var putExtra = putExtra || new qiniu.form_up.PutExtra();

                                formUploader.put(token, key, file, putExtra, function(respErr, respBody, respInfo) {
                                    if (respErr) {
                                        observer.error(respErr);
                                    } else {
                                        observer.complete(respBody);
                                    }
                                });

                                return {
                                    unsubscribe: function() {}
                                };
                            }
                        };

                        return subscription;
                    }
                };
                break;

            case STORAGE_TYPE.TENCENT:
                this.storageClient = new COS({
                    SecretId: options.tencent.secretId,
                    SecretKey: options.tencent.secretKey
                });
                break;
        }
    };

    // 处理选择的文件
    UploadMulti.prototype.handleFiles = function(obj, files) {
        var that = this;
        var options = this.options;

        // 检查文件数量是否超过限制
        var currentCount = this.uploadList.find('.layui-upload-item').length;
        var selectedCount = Object.keys(files).length;

        if (currentCount + selectedCount > options.maxCount) {
            layer.msg(`最多只能上传${options.maxCount}个文件`, {icon: 2});
            return;
        }

        // 验证文件类型
        var validFiles = {};
        var invalidFiles = [];

        Object.keys(files).forEach(function(key) {
            var file = files[key];
            if (that.validateFileType(file)) {
                validFiles[key] = file;
            } else {
                invalidFiles.push(file.name);
            }
        });

        // 提示不允许的文件类型
        if (invalidFiles.length > 0) {
            layer.msg(`不允许上传的文件类型: ${invalidFiles.join(', ')}`, {icon: 2});
        }

        // 处理合法文件
        if (Object.keys(validFiles).length > 0) {
            // 预览文件
            obj.preview(function(index, file, result) {
                var fileItem = that.createFileItem(file, result);
                that.uploadList.append(fileItem);

                if (options.autoUpload) {
                    that.uploadFile(file, fileItem, index);
                }

                // 更新文件列表
                that.updateFileList();
            });
        }
    };

    // 添加文件类型验证方法
    UploadMulti.prototype.validateFileType = function(file) {
        var options = this.options;

        // 如果没有设置允许的扩展名，则默认允许所有类型
        if (!options.acceptExts || options.acceptExts.trim() === '') {
            return true;
        }

        // 获取文件扩展名
        var ext = file.name.split('.').pop().toLowerCase();

        // 检查是否在允许的扩展名列表中
        var allowedExts = options.acceptExts.split(',').map(function(e) {
            return e.trim().toLowerCase();
        });

        if (!allowedExts.includes(ext)) {
            return false;
        }

        // 获取文件MIME类型
        var mimeType = file.type;

        // 如果浏览器无法获取MIME类型，则尝试通过扩展名推断
        if (!mimeType || mimeType === 'application/octet-stream') {
            mimeType = this.getMimeTypeFromExtension(file.name);
        }

        // 根据扩展名获取预期的MIME类型
        var expectedMimeTypes = this.getExpectedMimeTypes(ext);

        // 如果预期的MIME类型存在，验证实际MIME类型
        if (expectedMimeTypes.length > 0) {
            return expectedMimeTypes.some(function(expectedType) {
                if (expectedType.endsWith('/*')) {
                    var category = expectedType.split('/')[0];
                    return mimeType.startsWith(category + '/');
                }
                return mimeType === expectedType;
            });
        }

        // 如果没有预期的MIME类型，默认通过验证
        return true;
    };

// 根据文件扩展名获取预期的MIME类型
    UploadMulti.prototype.getExpectedMimeTypes = function(ext) {
        // 常见文件类型的MIME映射
        var mimeMap = {
            'jpg': ['image/jpeg'],
            'jpeg': ['image/jpeg'],
            'png': ['image/png'],
            'gif': ['image/gif'],
            'bmp': ['image/bmp'],
            'webp': ['image/webp'],
            'svg': ['image/svg+xml'],
            'mp4': ['video/mp4'],
            'webm': ['video/webm'],
            'ogg': ['video/ogg', 'audio/ogg'],
            'avi': ['video/x-msvideo'],
            'mov': ['video/quicktime'],
            'mp3': ['audio/mpeg'],
            'wav': ['audio/wav'],
            'pdf': ['application/pdf'],
            'doc': ['application/msword'],
            'docx': ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xls': ['application/vnd.ms-excel'],
            'xlsx': ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'ppt': ['application/vnd.ms-powerpoint'],
            'pptx': ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
            'txt': ['text/plain'],
            'json': ['application/json'],
            'xml': ['application/xml', 'text/xml'],
            'html': ['text/html'],
            'css': ['text/css'],
            'js': ['application/javascript'],
            'zip': ['application/zip', 'application/x-zip-compressed'],
            'rar': ['application/vnd.rar', 'application/x-rar-compressed'],
            '7z': ['application/x-7z-compressed']
        };

        return mimeMap[ext] || [];
    };

    // 根据文件扩展名推断MIME类型
    UploadMulti.prototype.getMimeTypeFromExtension = function(filename) {
        var ext = filename.split('.').pop().toLowerCase();

        // 常见文件类型的MIME映射（简化版）
        var mimeMap = {
            'jpg': 'image/jpeg',
            'jpeg': 'image/jpeg',
            'png': 'image/png',
            'gif': 'image/gif',
            'mp4': 'video/mp4',
            'pdf': 'application/pdf',
            'doc': 'application/msword',
            'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls': 'application/vnd.ms-excel',
            'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'txt': 'text/plain',
            'zip': 'application/zip'
        };

        return mimeMap[ext] || 'application/octet-stream';
    };

    // 创建文件项
    UploadMulti.prototype.createFileItem = function(file, result, isInit = false) {
        var that = this;
        var options = this.options;
        var fileType = options.fileType;
        var fileExt = file.name.split('.').pop().toLowerCase();
        var fileTypeClass = this.getFileTypeClass(fileExt);
        var isImage = fileType === FILE_TYPE.IMAGE;
        var isVideo = fileType === FILE_TYPE.VIDEO;

        var fileItem = $(`
        <div class="layui-upload-item ${isVideo ? 'layui-upload-item-video' : isImage ? '' : 'layui-upload-item-file ' + fileTypeClass}" data-name="${file.name}" data-url="${isInit ? result : ''}" data-key="${isInit ? (file.key || '') : ''}">
            ${isImage ? `<img src="${result}" alt="${file.name}" lay-src="${result}">` :
                isVideo ? `
                    <div class="play-icon"><i class="layui-icon layui-icon-play"></i></div>
                    ${isInit ? '' : `<img class="video-thumbnail" src="${result}" alt="${file.name}" style="display: none;">`}
                ` :
                        `<i class="fa ${this.getFileIcon(fileExt)}"></i><div class="layui-elip">${file.name}</div>`}
            ${isVideo && (isInit || options.videoThumbnail) ? `<div class="video-name">${file.name}</div>` : ''}
            ${!isInit ? `
            <div class="layui-upload-item-progress">
                <div class="layui-upload-item-progress-bar" style="width: 0%"></div>
            </div>
            ` : ''}
            <div class="layui-upload-item-actions">
                <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-danger layui-upload-delete">
                    <i class="layui-icon layui-icon-delete"></i>
                </a>
                <a href="javascript:;" class="layui-btn layui-btn-xs layui-btn-primary layui-upload-preview ${isInit ? '' : 'layui-hide'}">
                    <i class="layui-icon layui-icon-eye" style="color: #fff;"></i>
                </a>
            </div>
        </div>
    `);

        // 绑定删除事件
        fileItem.find('.layui-upload-delete').on('click', function() {
            fileItem.remove();
            that.updateFileList();
        });

        // 绑定预览按钮事件
        var previewHandler = function() {
            var url = fileItem.attr('data-url');
            var name = fileItem.attr('data-name');

            if (isImage) {
                layer.photos({
                    photos: {
                        "title": name,
                        "id": 1,
                        "start": 0,
                        "data": [
                            {
                                "alt": name,
                                "pid": 1,
                                "src": url,
                                "thumb": url
                            }
                        ]
                    },
                    shade: 0.8,
                    closeBtn: 1,
                    anim: 5
                });
            } else if (isVideo) {
                that.previewVideo(url, name);
            } else {
                that.previewDocument(url, name);
            }
        };

        fileItem.find('.layui-upload-preview').on('click', previewHandler);

        // 绑定文件项点击事件
        fileItem.on('click', function(e) {
            if (!$(e.target).closest('.layui-upload-item-actions').length) {
                previewHandler();
            }
        });

        // 视频缩略图处理
        if (isVideo && options.videoThumbnail && !isInit) {
            that.generateVideoThumbnail(fileItem, file, result);
        }

        return fileItem;
    };

    // 添加生成视频缩略图的方法
    UploadMulti.prototype.generateVideoThumbnail = function(fileItem, file, videoUrl) {
        var that = this;

        // 创建隐藏的视频元素
        var video = document.createElement('video');

        // 关键修复：设置crossOrigin属性
        video.crossOrigin = 'anonymous';

        video.src = videoUrl;
        video.preload = 'metadata';

        // 视频元数据加载完成后
        video.onloadedmetadata = function() {
            // 设置.currentTime可以触发seeked事件
            video.currentTime = 0.1; // 尝试获取第0.1秒的帧

            // 视频seek完成后
            video.onseeked = function() {
                // 创建canvas
                var canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // 绘制当前帧到canvas
                var ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                try {
                    // 尝试将canvas转换为图片URL
                    var thumbnailUrl = canvas.toDataURL('image/jpeg', 0.8);

                    // 更新视频项的缩略图
                    var thumbnailImg = fileItem.find('.video-thumbnail');
                    if (thumbnailImg.length) {
                        thumbnailImg.attr('src', thumbnailUrl).show();
                    } else {
                        fileItem.prepend(`<img class="video-thumbnail" src="${thumbnailUrl}" alt="${file.name}">`);
                    }
                } catch (e) {
                    console.error('无法生成视频缩略图:', e);
                    // 回退方案：使用默认视频图标
                    that.useDefaultVideoThumbnail(fileItem);
                }

                // 清理
                video.remove();
            };
        };

        // 错误处理
        video.onerror = function() {
            console.error('视频加载错误，无法生成缩略图:', video.error);
            // 回退方案：使用默认视频图标
            that.useDefaultVideoThumbnail(fileItem);
            video.remove();
        };

        // 添加到DOM但隐藏
        video.style.display = 'none';
        document.body.appendChild(video);
    };

    // 添加默认视频缩略图方法
    UploadMulti.prototype.useDefaultVideoThumbnail = function(fileItem) {
        // 使用Font Awesome视频图标作为默认缩略图
        fileItem.find('.play-icon').before(`
        <div class="default-video-thumbnail">
            <i class="fa fa-film"></i>
        </div>
    `);

        // 添加默认缩略图的样式
        fileItem.find('.default-video-thumbnail').css({
            'position': 'absolute',
            'width': '100%',
            'height': '100%',
            'background-color': '#000',
            'display': 'flex',
            'align-items': 'center',
            'justify-content': 'center',
            'color': '#fff',
            'font-size': '36px'
        });
    };

    // 上传文件
    UploadMulti.prototype.uploadFile = function(file, fileItem, index) {
        var that = this;
        var options = this.options;
        var progressBar = fileItem.find('.layui-upload-item-progress-bar');

        // 生成文件名
        var fileName = this.generateFileName(file);

        // 检查存储客户端是否初始化
        if (!this.storageClient) {
            layer.msg('存储客户端未初始化', {icon: 2});
            return;
        }

        // 根据存储类型选择上传方式
        var uploadPromise;

        switch(options.storageType) {
            case STORAGE_TYPE.ALIYUN:
                uploadPromise = this.storageClient.put(fileName, file, {
                    progress: function(p) {
                        var percent = Math.round(p * 100);
                        progressBar.width(percent + '%');
                        options.onProgress(file, percent);
                    }
                });
                break;

            case STORAGE_TYPE.QINIU:
                uploadPromise = new Promise(function(resolve, reject) {
                    // 使用新版七牛云SDK API
                    const config = {
                        useCdnDomain: true,
                        region: qiniu.region[options.qiniu.region || 'z0']
                    };

                    const observer = {
                        next: function(response) {
                            var percent = Math.round(response.total.percent);
                            progressBar.width(percent + '%');
                            options.onProgress(file, percent);
                        },
                        error: function(err) {
                            reject(err);
                        },
                        complete: function(res) {
                            resolve({
                                key: res.key,
                                url: options.qiniu.domain + '/' + res.key
                            });
                        }
                    };

                    // 使用新版SDK的upload方法
                    const subscription = qiniu.upload(
                        file, fileName, options.qiniu.uploadToken, {}, config
                    );

                    subscription.subscribe(observer);
                });
                break;

            case STORAGE_TYPE.TENCENT:
                uploadPromise = new Promise(function(resolve, reject) {
                    that.storageClient.putObject({
                        Bucket: options.tencent.bucket,
                        Region: options.tencent.region,
                        Key: fileName,
                        Body: file,
                        onProgress: function(progressData) {
                            var percent = Math.round(progressData.percent * 100);
                            progressBar.width(percent + '%');
                            options.onProgress(file, percent);
                        }
                    }, function(err, data) {
                        if (err) {
                            console.error('腾讯云上传失败:', err);
                            // 添加CORS相关错误提示
                            if (err.message.includes('CORS') || err.message.includes('network')) {
                                err.message += '\n\n可能原因：\n1. 腾讯云COS存储桶CORS配置不正确\n2. 网络连接问题\n3. 域名未备案(如适用)';
                            }
                            reject(err);
                        } else {
                            var url = `https://${options.tencent.bucket}.cos.${options.tencent.region}.myqcloud.com/${fileName}`;
                            resolve({
                                key: fileName,
                                url: url
                            });
                        }
                    });
                });
                break;
        }

        // 处理上传结果
        uploadPromise.then(function(res) {
            // 返回包含原文件名的完整信息
            var result = {
                key: res.key,
                url: res.url,
                originalName: file.name
            };

            fileItem.attr('data-url', res.url);
            fileItem.attr('data-key', res.key);

            // 显示预览按钮
            fileItem.find('.layui-upload-preview').removeClass('layui-hide');

            // 添加上传成功的视频名称
            if (options.fileType === FILE_TYPE.VIDEO) {
                fileItem.append(`<div class="video-name">${file.name}</div>`);
            }

            options.onSuccess(file, result);
            that.updateFileList();
        }).catch(function(err) {
            fileItem.append(`<div class="layui-upload-error">${err.message || '上传失败'}</div>`);
            options.onError(file, err);
        });
    };

    // 生成文件名
    UploadMulti.prototype.generateFileName = function(file) {
        var options = this.options;
        var ext = file.name.split('.').pop();
        var timestamp = new Date().getTime();
        var randomStr = Math.random().toString(36).substr(2, 8);

        // 构建完整保存路径
        var savePath = options.savePath || '';

        // 确保路径以斜杠结尾
        if (savePath && !savePath.endsWith('/')) {
            savePath += '/';
        }

        // 生成文件名（包含路径）
        return `${savePath}${timestamp}_${randomStr}.${ext}`;
    };

    // 更新文件列表
    UploadMulti.prototype.updateFileList = function() {
        var files = [];

        this.uploadList.find('.layui-upload-item').each(function() {
            var item = $(this);
            files.push({
                name: item.attr('data-name'),
                url: item.attr('data-url'),
                key: item.attr('data-key')
            });
        });

        if(files.length >= this.options.maxCount){
            this.uploadBtn.hide();
        }else{
            this.uploadBtn.show();
        }
        this.options.onChange(files);
    };

    // 预览视频
    UploadMulti.prototype.previewVideo = function(url, fileName) {
        if (!url) return;

        layer.open({
            type: 1,
            title: false,
            closeBtn: 1,
            area: ['800px', '500px'],
            shadeClose: true,
            maxmin: true,
            content: `
                    <div class="video-preview-container">
                        <div class="video-preview-name">${fileName}</div>
                        <div class="video-preview-player">
                            <video controls>
                                <source src="`+url+`" type="video/mp4">
                                您的浏览器不支持视频播放。
                            </video>
                        </div>
                    </div>
                `,
            success: function(layero, index) {
                // 视频加载完成后自动播放
                var video = layero.find('video')[0];
                video.play().catch(e => {
                    console.log('视频自动播放失败:', e);
                });
            }
        });
    };

    // 预览文档
    UploadMulti.prototype.previewDocument = function(url, fileName) {
        if (!url) return;

        // 在新窗口打开文档
        window.open(url, '_blank');
    };

    // 加载css
    layui.link("https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css");
    layui.link(layui.cache.base + "uploadMulti/uploadMulti.css");

    // 暴露接口
    exports('uploadMulti', function(options) {
        return new UploadMulti(options);
    });
});