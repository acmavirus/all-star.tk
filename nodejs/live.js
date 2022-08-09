const socket = require( 'socket.io' );
const app = require('express')();
const http = require('http');
var redis = require('redis'),
client = redis.createClient();

const server = http.createServer(app);
const request = require('request');
const port = 6666;

const io = socket.listen(server);

server.listen(port, () => console.log(`Listening on port ${port}`));


var connectedCount = 0;
console.log('---------Start socket---------');logMemory(connectedCount);


io.on("connection", socket => {
    socket.on('disconnect',function(){
        delete io.sockets[socket.id];
        delete io.sockets.sockets[socket.id];
        connectedCount--;
        if(!connectedCount) {
            logMemory(connectedCount);
        }
    });
    connectedCount++;
});

setInterval(function(){
    logMemory(connectedCount);
}, 30000);

var prefix = 'live_';

var key_miennam = prefix + 'mien_nam',
    key_mienbac = prefix + 'mien_bac',
    key_mientrung = prefix + 'mien_trung';

var params_miennam = {"code":"xsmn"},
    params_mienbac = {"code":"xsmb"},
    params_mientrung = {"code":"xsmt"};

setInterval( async function() {
    let today = new Date();
    let hourCurrent = today.getHours();
    let data = null;
    let data_cache = null;
    if(hourCurrent >= 16 && hourCurrent < 17){
        data_cache = await getDataRedis(key_miennam);
        data_cache = JSON.parse(data_cache);
        data = await getData(params_miennam);
        console.log('Check number new:', JSON.stringify(data).length, JSON.stringify(data_cache).length);
        if (!data || JSON.stringify(data).length > JSON.stringify(data_cache).length) {
            client.set(key_miennam, JSON.stringify(data),'EX',60);
            io.emit('data', data);
            // await updateCache();
            console.log('Push all client !');
        }
    }

    if(hourCurrent >= 17 && hourCurrent < 18){
        data_cache = await getDataRedis(key_mientrung);
        data_cache = JSON.parse(data_cache);
        data = await getData(params_mientrung);
        //console.log('Check number new:', JSON.stringify(data).length, JSON.stringify(data_cache).length);
        if (!data || JSON.stringify(data).length > JSON.stringify(data_cache).length) {
            client.set(key_mientrung, JSON.stringify(data),'EX',60);
            io.emit('data', data);
            // await updateCache();
            console.log('Push all client !');
        }
    }

    if(hourCurrent >= 18 && hourCurrent < 19){
        data_cache = await getDataRedis(key_mienbac);
        data_cache = JSON.parse(data_cache);
        data = await getData(params_mienbac);
        //console.log('Check number new:', JSON.stringify(data).length, JSON.stringify(data_cache).length);
        if (!data || JSON.stringify(data).length > JSON.stringify(data_cache).length) {
            client.set(key_mienbac, JSON.stringify(data),'EX',60);
            io.emit('data', data);
            // await updateCache();
            console.log('Push all client !');
        }
    }
}, 1000);


/*socket live hiepphu*/
const prefixhp = 'socket_';

const key_live_hp = prefixhp + 'live';

setInterval( async function() {
    let data = await getDataRedis(key_live_hp);
    if (!data) {
        let dataObject = await getDataSocketHP();
        data = JSON.stringify(dataObject)
        client.set(key_live_hp, data ,'EX',60);
    }
    io.emit('datasockethp', data);
}, 10000);

function logMemory(connectedCount) {
    console.log("Total :",connectedCount);
    let memory = process.memoryUsage();
    console.log((new Date()).toLocaleDateString('vi','H:i d-m-Y'), 'RSS ',memory.rss, ', Heap Used (MB):', memory.heapUsed / (1024 * 1024) , ' Heap Total (MB) :', memory.heapTotal  / (1024 * 1024));
}

async function getDataRedis(key) {
    return new Promise((resolve, reject) => {
        client.get(key, function(error, result) {
            if (error) {
                throw error;
                //reject(error);
            }
            resolve(result);
        });
    });
}

async function updateCache() {
    try {
        request('https://axoso.net/debug/update_cache', function (error, response, body) {
            console.log('Update cache axoso:', response && response.statusCode); // Print the response status code if a response was received
        });

        request('https://ketquaxoso.net/debug/update_cache', function (error, response, body) {
            console.log('Update cache ketquaxoso:', response && response.statusCode); // Print the response status code if a response was received
        });
    } catch (error) {
        console.error(error);
    }
}

async function getData(data) {
    return new Promise((resolve, reject) => {
        request.post('https://dataxoso.webest.asia/api/v1/result/getLive', {
            json: data
        }, (error, response, body) => {
            if (error) {
                throw error;
                //reject(error);
            }
            resolve(body.data.data);
        })
    });
}

async function getDataSocketHP() {
    return new Promise((resolve, reject) => {
        request.get('https://datasoccer.webest.asia/api/v1/match/get_live_socket', (error, response, body) => {
            if (error) {
                throw error;
                reject(error);
            }
            body = JSON.parse(body);
            let list_live = body.data.data;
            console.log('statusCode:', response && response.statusCode,' Live total match => ',Object.keys(list_live).length);
            resolve(list_live);
        })
    });
}