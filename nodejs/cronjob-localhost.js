const Cronjob = require('node-cron');
const Shell = require('child_process');
Cronjob.schedule('*/1 * 16,17,18 * * *', () => {
    Shell.exec('php D://laragon/__project-new/dataxoso.webest.asia/index.php crawler xoso getLiveResult', function (error, stdout, stderr) {
        console.log("Start crawl realtime result !");
        console.log(stdout);
    });
});

Cronjob.schedule('*/1 * * * *', () => {
    Shell.exec('php D://laragon/__project-new/dataxoso.webest.asia/index.php crawler xoso getLiveKeno', function (error, stdout, stderr) {
        console.log("Start crawl keno realtime result !");
        console.log(stdout);
    });
});