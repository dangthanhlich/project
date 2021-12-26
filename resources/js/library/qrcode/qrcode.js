class QRCode {
    constructor(canvasId, videoId, interval = 200, isMobile = true) {
        this.detectedCodes = [];
        this.video = null;
        this.detector = null;
        this.canvas = document.getElementById(canvasId);
        this.video = document.getElementById(videoId);
        this.ctx = this.canvas.getContext("2d");
        this.intervalId = null;
        this.interval = interval;
        this.isMobile = isMobile;
    }

    start(detectedCallback = () => {}) {
        if (!('BarcodeDetector' in window)) {
            alert("BarcodeDetector is not available.");
        } else {
            this.detectedCallback = detectedCallback;
            this.detector = new BarcodeDetector({ formats: ['qr_code'] });

            // Check for a camera
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                let videoSize = !this.isMobile
                    ? this.getVideoSize()
                    : this.video.offsetWidth;
                this.video.width = videoSize;
                this.video.height = videoSize;
                this.canvas.width = videoSize;
                this.canvas.height = videoSize;
                const videoOpts = {
                    width: {
                        ideal: videoSize
                    },
                    height: {
                        ideal: videoSize
                    },
                    facingMode: 'environment'
                };
                const opt = {
                    video: videoOpts,
                    audio: false
                };

                this.startCam(opt);

                // detect every interval
                this.intervalId = setInterval(this.detectCode.bind(this), this.interval);
            }
        }
    }

    stop() {
        clearInterval(this.intervalId);
        this.stopCam();
    }

    async startCam(options) {
        // Start video stream
        this.video.srcObject = await navigator.mediaDevices.getUserMedia(options);
    }

    stopCam() {
        // Stop video stream
        if (this.video.srcObject) {
            this.video.srcObject.getTracks().forEach((track) => {
                track.stop();
            });
        }
    }

    async detectCode() {
        try {
            this.detectedCodes = await this.detector.detect(this.video);

            if (this.detectedCodes && this.detectedCodes.length > 0) {
                this.drawPosition(this.detectedCodes[0].boundingBox);
                this.detectedCallback(this.detectedCodes);
            } else {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            }
        } catch (err) {
            console.log(err);
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        }
    }

    drawPosition(boundingBox) {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        if (!boundingBox) return;

        this.ctx.lineWidth = 4;
        this.ctx.strokeStyle = "#FF0000";
        let DVWRatio = this.canvas.width/this.video.videoWidth;
        let topV = (this.canvas.height - this.video.videoHeight*DVWRatio)/2;

        let x = boundingBox.x * DVWRatio;
        let y = boundingBox.y * DVWRatio + topV;
        let width = boundingBox.width * DVWRatio;
        let height = boundingBox.height * DVWRatio;

        this.ctx.strokeRect(x, y, width, height);
    }

    getVideoSize() {
        let sW = window.screen.availWidth;
        let sH = window.screen.availHeight;

        return ((sH <= sW)? sH: sW);
    }
};