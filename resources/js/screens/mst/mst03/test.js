// QR code
let qrcode = new QRCode('canvas', 'video');


// create pdf
window.onload = Sii.onFailure();                                           // Call an initialize function after loading the page.

async function printPDF() {
    var element = document.getElementById('template');
    var imgData = '';
    var imgOpt = {
        margin:       [0, 0, 0, 0],
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 5, useCORS: true },
        jsPDF:        { unit: 'px', hotfixes: ["px_scaling"], format: [340, 400], orientation: 'portrait'}
    };
    var opt = {
        margin:       [0, 0, 0, 0],
        filename:     `document.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: [50, 60], orientation: 'portrait'}
    };

    await html2pdf().from(element).set(imgOpt).outputImg('datauri').then(function(img) {
        imgData = img;
    });

    html2pdf().from('').set(opt).toPdf().get('pdf').then(function (pdf) {
        pdf.setPage(1);
        pdf.addImage(imgData, "jpeg", 0, 0, 50, 60);
    }).outputPdf('datauri').then(function (pdfout) {
        let index = pdfout.indexOf(',') + 1;                 // Read in Base64 format.
        pdfData = pdfout.slice(index);                       // Remove unnecessary part.
        var printer = new Sii(pdfData);
        printer.print();
    });
}

// NFC
function onReadingError() {
    alert("Cannot read data from this NFC tag");
}

function onReadingText(message, serialNumber) {
    let totalRecord = message.records.length;
    let msg = "Serial Number: " + serialNumber + "\n";
    msg += "Records: " + totalRecord + "\n";

    for(let i = 0; i < totalRecord; i++) {
        const record = message.records[i];
        const textDecoder = new TextDecoder(record.encoding);
        msg += "Text: " + textDecoder.decode(record.data) + "\n";
    }
    alert(msg);
}

scanButton.addEventListener("click", async function() {
    Nfc.init();
    Nfc.startScanning(onReadingText, onReadingError);
});
