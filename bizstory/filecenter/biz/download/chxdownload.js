/**
 *  CHXUpload HTML5
 */

function setConfing () {
    // 파일을 읽어 출력하는 서버 사이드 스크립트 URL 주소
    this.ServerURL = 'http://220.90.128.141/filecenter/demo/download/download.php';

    // 파일 정보를 리턴하는 서버 사이드 스크립트 URL 주소
    this.ServerFileInfo = 'http://220.90.128.141/filecenter/demo/download/fileinfo.php';


    // 파일 하나의 최대 허용 크기
    this.AllowMaxFileSize = 10000 * 1000 * 1000; // 10G

    // 전체 파일의 최대 허용 크기
    this.AllowMaxFileTotalSize = 100000 * 1000 * 1000; // 100G

    // 전체 파일의 최대 허용 개수
    this.AllowMaxFileNumber = 100;

    // 파일 선택 창의 파일 유형 필터링
    this.FileAccept = '*.*';

    // 아래 설정은 가능하면 수정하지 않는다.
    this.DownloadFiles = [];
    this.SuccessFiles = [];
    this.reader = null;
    this.DownloadTotalSize = 0;
    this.LoadedTotalSize = 0;
    this.callme = null;
    this.otherFiles = [];
    this.AutoStart = true;
}

function CHXDownload() {
    this.toType = (function (global) {
        var toString = CHXDownload.prototype.toString,
            re = /^.*\s(\w+).*$/;
        return function (obj) {
            if (obj === global) {
                return 'global';
            }
            return toString.call(obj).replace(re, '$1').toLowerCase();
        };
    }(this));

    this.undefined = function (obj) {
        return obj === void(0); // obj === undefined;
    };
    this.support = (!this.undefined(File) && !this.undefined(Blob) && !this.undefined(FileList) &&
        (!!Blob.prototype.webkitSlice || !!Blob.prototype.mozSlice || !!Blob.prototype.slice || false));

    setConfing.call(this);
    return this;
}

CHXDownload.prototype = {
// -----------------------------------------------------------------------------
//
//
    makeRandomString : function () {
        var chars = '_-$@!#0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz',
            len = 32, clen = chars.length, rData = '', i = 0, rnum;

        for (; i < len; i++) {
            rnum = Math.floor(Math.random() * clen);
            rData += chars.substring(rnum, rnum + 1);
        }
        return rData;
    },

    run : function () {
        var self = this,
            removeButton;

        if (!this.support) {
            console.log('CHXDownload가 현재 브라우저를 지원하지 않습니다.');
            return false;
        }

        removeButton = document.getElementById('IdRemoveFileButton');
        if (removeButton) {
            var list = document.getElementById('IdDownloadFileList').parentNode, i, checkbox, inputElem;

            this.addEvent(removeButton, 'mouseover', function () {
                inputElem = list.getElementsByTagName('INPUT');

                for (i = 0; i < inputElem.length; i++) {
                    checkbox = inputElem[i];
                    if (checkbox.getAttribute('type').toLowerCase() === 'checkbox' && checkbox.checked) {
                        this.className = 'chxdownload_remove_button_mouseover';
                        break;
                    }
                }
            });
            this.addEvent(removeButton, 'mouseout', function () {
                this.className = 'chxdownload_remove_button';
            });
            this.addEvent(removeButton, 'click', function () {
                var remove = [];
                inputElem = list.getElementsByTagName('INPUT');

                for (i = 0; i < inputElem.length; i++) {
                    checkbox = inputElem[i];
                    if (checkbox.getAttribute('type').toLowerCase() === 'checkbox' && checkbox.checked) {
                        remove.push(checkbox);
                    }
                }
                if (remove.length > 0) {
                    for (i = 0; i < remove.length; i++) {
                        list.removeChild(remove[i].parentNode.parentNode);
                        self.removeItem(remove[i].id);
                    }
                }
            });
        }

        this.progressBar = document.getElementById('IdProgressBar');
        this.label = document.getElementById('IdProgressBarLabel');
        this.progressBarTotal = document.getElementById('IdTotalProgressBar');
        this.labelTotal = document.getElementById('IdTotalProgressBarLabel');
        this.downloadWrapper = document.getElementById('IdDownloadWrapper');
        this.downloadingFileSize = document.getElementById('IdDownloadingFileSize');
        this.downloadingTotalFileSize = document.getElementById('IdDownloadingTotalFileSize');
        this.downloadWrapperBackground = this.downloadWrapper.style.background;
        this.clear();

        return true;
    },

    setOtherFiles : function (files) {
        this.otherFiles = files;
        if (this.otherFiles.length > 0) {
            this.getFileInfo();
        } else {
            var el = this.downloadWrapper.getElementsByTagName('INPUT'), i;
            for (i = 0; i < el.length; i++) {
                this.downloadWrapper.removeChild(el[i].parentNode.parentNode);
            }
            this.downloadingTotalFileSize.innerHTML = '(0 Bytes)';
            this.clear();
            alert('다운로드 할 파일을 하나 이상 선택해 주세요.');
        }
    },

    removeItem : function (name) {
        var i = 0;
        for (; i < this.DownloadFiles.length; i++) {
            if (this.DownloadFiles[i].name === name) {
                this.DownloadTotalSize -= this.DownloadFiles[i].size;
                this.DownloadFiles.splice(i, 1);
            }
        }
        this.updateDownloadingTotalSize();
    },
    getFileInfo : function () {
        var xhr = new XMLHttpRequest(),
            self = this,
            files = [];

        xhr.open('POST', this.ServerFileInfo, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.responseType = 'text';

        xhr.addEventListener("error", function () {
            alert("파일 전송 중 오류: " + this.error.code);
        }, false);

        xhr.addEventListener('load', function () {
            var rData = {}, file, i = 0;
            if (this.status === 200) {
                // json array
                rData = JSON.parse(this.response.replace(/^\s*/g, '').replace(/\s*$/g, ''));
                for (; i < rData.length; i++) {
                    file = rData[i];
                    file.size = parseInt(file.size);
                    if (file.name !== '' && file.size > 0 && file.type !== '') {
                        files.push(file);
                    }
                }
            } else {
                alert('HTTP 오류: ' + xhr.status);
            }
        });
        xhr.addEventListener('loadend', function () {
            setTimeout(function () {
                if (files.length > 0) {
                    self.addFiles(files);
                }
            }, 100);
        });
        xhr.send('qfiles=' + self.otherFiles.join(','));
    },
    addFiles : function (files) {
        var i, file, num, list, row, col, checkbox;

        list = document.getElementById('IdDownloadFileList');
        num = files.length;
        if (num > this.AllowMaxFileNumber) {
            num = this.AllowMaxFileNumber;
        }

        for (i = 0; i < num; i++) {
            file = files[i];
            if (this.DownloadTotalSize + file.size > this.AllowMaxFileTotalSize) {
                continue;
            }
            if (file.size === 0 || file.size > this.AllowMaxFileSize) {
                continue;
            }
            if (this.dupFileCheck(file)) {
               continue;
            }

            this.DownloadTotalSize += file.size;
            this.DownloadFiles.push(file);

            row = list.cloneNode(true);
            row.setAttribute('id', this.makeRandomString());
            file.id = row.id;
            col = row.firstChild;

            while (col.nextSibling) {
                if (col.className === 'chxdownload_filename' ||
                    col.className === 'chxdownload_filesize' ||
                    col.className === 'chxdownload_status')
                {
                    col.style.backgroundColor = '#fff';
                }
                switch (col.className) {
                    case 'chxdownload_filename' :
                        col.style.textAlign = 'left';
                        checkbox = document.createElement('input');
                        checkbox.setAttribute('type', 'checkbox');
                        checkbox.style.verticalAlign = 'middle';
                        checkbox.style.marginTop = '0';
                        checkbox.setAttribute('id', file.name);
                        col.replaceChild(document.createTextNode(file.name), col.firstChild);
                        col.insertBefore(checkbox, col.firstChild);
                        break;
                    case 'chxdownload_filesize' :
                        col.replaceChild(document.createTextNode(this.formatSize(file.size) + '\u00a0'), col.firstChild);
                        col.style.textAlign = 'right';
                        break;
                    case 'chxdownload_status' :
                        col.replaceChild(document.createTextNode('대기'), col.firstChild);
                        break;
                }
                col = col.nextSibling;
            }
            list.parentNode.appendChild(row);
        }
        if (this.DownloadTotalSize > 0) {
            this.updateDownloadingTotalSize();
            if (this.AutoStart) {
                this.startDownload();
            }
        }
    },
    dupFileCheck : function (addFile) {
        var i = 0, file;
        for (; i < this.DownloadFiles.length; i++) {
            file = this.DownloadFiles[i];
            if (addFile.name === file.name && addFile.size === file.size && addFile.type === file.type) {
                return true;
            }
        }
        return false;
    },
    updateDownloadingSize : function (size, downloaded) {
        if (downloaded) {
            this.downloadingFileSize.replaceChild(document.createTextNode('(' +
                this.formatSize(downloaded) + '/' + this.formatSize(size) + ')'),
                this.downloadingFileSize.firstChild);
        } else {
            this.downloadingFileSize.replaceChild(document.createTextNode('(' + this.formatSize(size) + ')'),
                this.downloadingFileSize.firstChild);
        }
    },
    updateDownloadingTotalSize : function (downloaded) {
        if (downloaded) {
            this.downloadingTotalFileSize.replaceChild(document.createTextNode('(' +
                this.formatSize(downloaded) + '/' + this.formatSize(this.DownloadTotalSize) + ')'),
                this.downloadingTotalFileSize.firstChild);
        } else {
            this.downloadingTotalFileSize.replaceChild(document.createTextNode('(' + this.formatSize(this.DownloadTotalSize) + ')'),
                this.downloadingTotalFileSize.firstChild);
        }
        if (this.DownloadTotalSize > 0) {
            this.downloadWrapper.style.background = 'none';
        } else {
            this.downloadWrapper.style.background = this.downloadWrapperBackground;
        }
    },
    formatSize: function(size) {
        if (size < 1024) {
            return size + ' Bytes';
        } else if (size < 1024 * 1024) {
            return (size / 1024.0).toFixed(0) + ' KB';
        } else if (size < 1024 * 1024 * 1024) {
            return (size / 1024.0 / 1024.0).toFixed(1) + ' MB';
        } else {
            return (size / 1024.0 / 1024.0 / 1024.0).toFixed(1) + ' GB';
        }
    },
    updateStatus : function (file, status) {
        var node, row = document.getElementById(file.id);
        node = row.getElementsByClassName('chxdownload_status')[0];
        node.replaceChild(document.createTextNode(status), node.firstChild);
    },
    dataLoadHandler : function (file) {
        var name = file.name,
            type = file.type || 'application/octet-stream',
            size = file.size,
            self = this,
            req = new XMLHttpRequest(),
            theFormData = [],
            bytes,
            total;

        req.open('POST', this.ServerURL, true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        theFormData.push('filesize=' + size);
        theFormData.push('filename=' + name);
        theFormData.push('filetype=' + type);

        req.addEventListener("error", function () {
            alert("파일 다운로드 중 오류가 발생했습니다.");
        }, false);

        req.addEventListener('progress', function (evt) {
            if (evt.lengthComputable) {
                bytes = evt.loaded;
                total = evt.total;
                if (self.progressBar) {
                    self.progressBar.value = Math.round((bytes / total) * 100);
                    self.progressBar.textContent = self.progressBar.value; // Fallback
                    self.updateDownloadingSize(total, bytes);
                }
                if (self.label) {
                    self.label.innerHTML = self.progressBar.value + '%';
                }
                if (self.progressBarTotal) {
                    self.progressBarTotal.value = Math.round(((self.LoadedTotalSize + bytes) / self.DownloadTotalSize) * 100);
                    self.progressBarTotal.textContent = self.progressBarTotal.value;
                    self.updateDownloadingTotalSize(self.LoadedTotalSize + bytes);
                }
                if (self.labelTotal) {
                    self.labelTotal.innerHTML = self.progressBarTotal.value + '%';
                }
            }
        });

        req.addEventListener('loadend', function () {
            self.LoadedTotalSize += size;
            self.updateStatus(file, '완료');
            setTimeout(function () {
                self.startDownload();
            }, 100);

            self.SuccessFiles.push({
                'name' : name,
                'size' : size,
                'type' : type
            });
        });

        req.responseType = 'blob';
        req.addEventListener('load', function () {
            var response = '',
                link = null;

            if (this.status === 200) {
                response = this.response;
                if (typeof window.chrome !== 'undefined') {
                    link = document.createElement('a');
                    link.href = window.URL.createObjectURL(req.response);
                    link.download = name;
                    link.click();
                } else if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    var blob = new Blob([req.response], { type: 'application/force-download' });
                    window.navigator.msSaveBlob(blob, name);
                    console.log('msie');
                } else {
                    var file = new File([req.response], name, { type: 'application/force-download' });
                    window.open(URL.createObjectURL(file));
                }
            } else {
                alert('HTTP 오류: ' + req.status);
            }
        });
        req.send(theFormData.join('&'));
    },
    startDownload : function () {
        var file = this.DownloadFiles.shift();
        if (file) {
            this.updateDownloadingSize(file.size);
            this.updateStatus(file, '전송중');
            this.dataLoadHandler(file);
        } else {
            this.clear();
            this.callme(this.SuccessFiles);
        }
    },
    clear : function () {
        this.DownloadFiles = [];
        this.DownloadTotalSize = 0;
        this.LoadedTotalSize = 0;
    },
    addEvent : function (evTarget, evType, evHandler) {
        if (evTarget.addEventListener) {
            evTarget.addEventListener(evType, evHandler, false);
        } else {
            evTarget.attachEvent('on' + evType, evHandler);
        }
    },
    stopEvent : function (ev) {
        if (ev && ev.preventDefault) {
            ev.preventDefault();
            ev.stopPropagation();
        } else {
            ev = ev || window.event;
            ev.cancelBubble = true;
            ev.returnValue = false;
        }
    }
// -----------------------------------------------------------------------------
//
//
};
