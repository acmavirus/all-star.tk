// const Cronjob = require('node-cron');
// const Shell = require('child_process');
// Cronjob.schedule('*/1 * 16,17,18 * * *', () => {
//     Shell.exec('/RunCloud/Packages/php73rc/bin/php /home/runcloud/webapps/dataxoso/live/index.php crawler xoso getLiveResult', function (error, stdout, stderr) {
//         console.log("Start crawl realtime result !");
//         console.log(stdout);
//     });
// });

// Cronjob.schedule('*/1 * * * *', () => {
//     Shell.exec('/RunCloud/Packages/php73rc/bin/php /home/runcloud/webapps/dataxoso/live/index.php crawler xoso getLiveKeno', function (error, stdout, stderr) {
//         console.log("Start crawl keno realtime result !");
//         console.log(stdout);
//     });
// });



const Cronjob = require('node-cron');
const Shell = require('child_process');
Cronjob.schedule('*/1 * 16,17,18 * * *', () => {
    Shell.exec('/usr/bin/php7.3 /home/dataxoso.webest.asia/public_html/index.php crawler CrawlerData getLiveResult', function (error, stdout, stderr) {
        console.log("Start crawl realtime result !");
        console.log(stdout);
    });
});

Cronjob.schedule('*/1 * * * *', () => {
    Shell.exec('/usr/bin/php7.3 /home/dataxoso.webest.asia/public_html/index.php crawler xoso getLiveKeno', function (error, stdout, stderr) {
        console.log("Start crawl keno realtime result !");
        console.log(stdout);
    });
});