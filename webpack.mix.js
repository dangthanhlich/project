const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//     .postCss('resources/css/app.css', 'public/css', [
//         //
//     ])
//     .version();

let fs = require('fs');

let jsDirs = [
    {
        in: 'resources/js',
        out: 'js',
    },
    // {
    //     in: 'resources/js/library/jquery-validation',
    //     out: 'js/library/jquery-validation',
    // },
    {
        in: 'resources/js/screens/auth',
        out: 'js/screens/auth',
    },
    {
        in: 'resources/js/screens/cas/cas01',
        out: 'js/screens/cas/cas01',
    },
    {
        in: 'resources/js/screens/cas/cas02',
        out: 'js/screens/cas/cas02',
    },
    {
        in: 'resources/js/screens/cas/cas03',
        out: 'js/screens/cas/cas03',
    },
    {
        in: 'resources/js/screens/cas/cas04',
        out: 'js/screens/cas/cas04',
    },
    {
        in: 'resources/js/screens/cas/cas07',
        out: 'js/screens/cas/cas07',
    },
    {
        in: 'resources/js/screens/cas/cas05',
        out: 'js/screens/cas/cas05',
    },
    {
        in: 'resources/js/screens/cas/cas06',
        out: 'js/screens/cas/cas06',
    },
    {
        in: 'resources/js/screens/cas/cas09',
        out: 'js/screens/cas/cas09',
    },
    {
        in: 'resources/js/screens/cas/cas12',
        out: 'js/screens/cas/cas12',
    },
    {
        in: 'resources/js/screens/mst/mst01',
        out: 'js/screens/mst/mst01',
    },
    {
        in: 'resources/js/screens/mst/mst02',
        out: 'js/screens/mst/mst02',
    },
    {
        in: 'resources/js/screens/mst/mst03',
        out: 'js/screens/mst/mst03',
    },
    {
        in: 'resources/js/screens/mst/mst05',
        out: 'js/screens/mst/mst05',
    },
    {
        in: 'resources/js/screens/pal/pal01',
        out: 'js/screens/pal/pal01',
    },
    {
        in: 'resources/js/screens/rep/rep01',
        out: 'js/screens/rep/rep01',
    },
    {
        in: 'resources/js/screens/com/com02',
        out: 'js/screens/com/com02',
    },
];

let cssDirs = [
    {
        in: 'resources/css',
        out: 'css',
    },
    {
        in: 'resources/css/screens',
        out: 'css/screens',
    },
    {
        in: 'resources/css/screens/cas/cas05',
        out: 'css/screens/cas/cas05',
    },
    {
        in: 'resources/css/screens/cas/cas06',
        out: 'css/screens/cas/cas06',
    },
    {
        in: 'resources/css/screens/cas/cas07',
        out: 'css/screens/cas/cas07',
    },
    {
        in: 'resources/css/screens/cas/cas04',
        out: 'css/screens/cas/cas04',
    },
    {
        in: 'resources/css/screens/pal/pal01',
        out: 'css/screens/pal/pal01',
    },
    {
        in: 'resources/css/screens/cas/cas12',
        out: 'css/screens/cas/cas12',
    },
];

let getFiles = function (dir) {
    // get all 'files' in this directory
    // filter directories
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};

jsDirs.forEach(function (path) {
    getFiles(path.in).forEach(function (filepath) {
        mix.js(path.in + '/' + filepath, path.out);
    });
});

cssDirs.forEach(function (path) {
    getFiles(path.in).forEach(function (filepath) {
        mix.css(path.in + '/' + filepath, path.out);
    });
});

mix.copy('resources/js/library/jquery-validation/additional-setting.js', 'public/js/library/jquery-validation/additional-setting.js');
mix.copy('resources/js/library/qrcode/qrcode.js', 'public/js/library/qrcode/qrcode.js');
mix.copy('resources/js/library/printer/printer.js', 'public/js/library/printer/printer.js');
mix.copy('resources/js/library/nfc/nfc.js', 'public/js/library/nfc/nfc.js');
mix.copy('resources/js/library/signature_pad/signature_pad.min.js', 'public/js/library/signature_pad/signature_pad.min.js');
mix.copy('resources/js/library/signature_pad/signature.js', 'public/js/library/signature_pad/signature.js');
mix.copy('resources/js/screens/mst/mst03/test.js', 'public/js/screens/mst/mst03/test.js');

mix
.version();
mix.disableNotifications();