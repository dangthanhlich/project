var pdfData = '';

const PAPER_WIDTH = {'58': '58', '76.2': '76.2', '80': '80', '100': '100', '105': '105', '112': '112'};
const ERROR_DIALOG = {'YES': 'yes', 'NO': 'no'};
const DRAWER = {'YES': 'yes', 'NO': 'no'};
const CUT_TYPE = {'FULL': 'full', 'PARTIAL': 'partial', 'OFF': 'off'};
const CUT_FEED = {'YES': 'yes', 'NO': 'no'};
const BOTTOM_MARGIN = {'TO_BOTTOM_PDF_CONTENT': '0', 'TO_BOTTOM_PDF_FILE': '-1'};
const FIT_TO_WIDTH = {'YES': 'yes', 'NO': 'no'};
const ROTATION = {'0': '0', '90': '90', '180': '180', '270': '270'};
const DITHER = {'YES': 'yes', 'NO': 'no'};

class Sii {
    /**
     * @param data pdf data in base64
     *  For Android: data's size Approximately 200 KB
     *  (PDF size: 150 KB) ﻿before Base64 conversion
     */
    constructor(data) {
        let curPage = window.location.href;
        this.schema = 'siiprintagent://1.0/print?';
        this.option = {
            'Data': encodeURIComponent(data),                   // Note: need encodeURI

            // require options and no need to change
            'Format': 'pdf',
            'CallbackSuccess': encodeURIComponent(curPage),     // URL for printing success
            'CallbackFail': encodeURIComponent(curPage),        // URL for printing fail
            'SelectOnError': 'yes',                             // Printer selection dialog displayed when a failure occurs

            // optional
            'Timeout': '15000',                                 // ﻿The setting of operation timeout (ms) in this software ﻿(10000 to 300000 [ms])
            'ErrorDialog': ERROR_DIALOG.YES,                    // ﻿Error notification by this software
            'Drawer': DRAWER.NO,                                // ﻿Drawer driving setting
            'CutType': CUT_TYPE.PARTIAL,                        // ﻿Cutter setting
            'CutFeed': CUT_FEED.YES,                            // ﻿Feed selection at the cutting
            'BottomMargin': BOTTOM_MARGIN.TO_BOTTOM_PDF_FILE,   // ﻿Paper feed length setting from the position at minimum bottom margin
            'PaperWidth': PAPER_WIDTH["58"],                    // ﻿Paper width selection in mm
            'LeftRightMargin': '0',                             // ﻿Left and right margin setting for paper width
            'FitToWidth': FIT_TO_WIDTH.NO,                      // ﻿Expanding or reducing the PDF's page in accordance with the specified print area by PaperWidth query
            'Rotation': ROTATION["0"],                          // ﻿Specifying print direction
            'Dither': DITHER.NO                                 // ﻿PDF dithering selection
        }; // default option
    }

    print() {
        let url = this.genPrintQuery();
        if (url) {
            location.href = url;
        } else {
            alert('Can not create print query');
        }
    }

    genPrintQuery() {
        let url = this.schema;
        for (const key in this.option) {
            if (this.option[key]) {
                url += key + '=' + this.option[key] + '&';
            }
        }
        return url.substr(0, url.length - 1);   // remove the last '&'
    }

    /**
     * This method use to show error if exist on screen specified by CallbackFail (URL for printing fail)
     */
    static onFailure() {
        let query = location.search;                                          // Get query.
        let code = query.match(/Code=([^&#]*)/);                              // Get 'Code' in query.
        let message = query.match(/Message=([^&#]*)/);                        // Get 'Message' in query.
        if (code) {                                                           // Check whether an error occurred.
            alert(`Error.\n\nCode = ${decodeURIComponent(code[1])}\nMessage = ${decodeURIComponent(message[1])}`);
            window.open('', '_self').close();                                 // close screen specified by CallbackFail (URL for printing fail)
        }
    }
}