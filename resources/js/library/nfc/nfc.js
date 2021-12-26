class Nfc {
    static checkChrome(ver = 89) {
        if (/Chrome\/(\d+\.\d+.\d+.\d+)/.test(navigator.userAgent)){
            if (parseInt(RegExp.$1) < ver) {
                alert("Make sure run this feature on Chrome" + ver + ' or above!');
                return false;
            }
            return true;
        }
        alert("Make sure run this feature on Chrome" + ver + ' or above!');
        return false;
    }

    static hasFeature() {
        return ('NDEFReader' in window);
    }

    static async checkPermission() {
        const permissionStatus = await navigator.permissions.query({ name: "nfc" });
        return (permissionStatus.state === "granted");
    }

    static reader = null;

    static onScanning = false;

    static init() {
        if(!Nfc.reader) {
            if (Nfc.checkChrome() && Nfc.hasFeature()) {
                Nfc.reader = new NDEFReader();
            } else {
                alert("Web NFC is not available.");
            }
        }
    }

    static async startScanning(msgProcessingCallback, errCallback) {
        try {
            if (!Nfc.onScanning) {
                await Nfc.reader.scan();
                Nfc.onScanning = true;
                alert("start scanning .");
                Nfc.reader.addEventListener("reading", function(nfcData){
                    msgProcessingCallback(nfcData.message, nfcData.serialNumber);
                });
                Nfc.reader.addEventListener("readingerror", errCallback);
            }
        } catch (error) {
            alert(error);
        }
    }
}