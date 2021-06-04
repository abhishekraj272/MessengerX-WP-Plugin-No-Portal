function isMobileDevice() {
	return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function getJSON(url, method = 'GET') {
	return new Promise(function (resolve, reject) {
		let xhr = new XMLHttpRequest();
		xhr.open(method, url);
		xhr.onload = function () {
			if (this.status >= 200 && this.status < 300) {
				resolve(JSON.parse(xhr.response));
			} else {
				reject({
					status: this.status,
					statusText: xhr.statusText
				});
			}
		};
		xhr.onerror = function () {
			reject({
				status: this.status,
				statusText: xhr.statusText
			});
		};
		xhr.send();
	});
}

function deviceId() {
	function e() {
		return Math.floor(65536 * (1 + Math.random()))
			.toString(16)
			.substring(1);
	}
	return e() + e() + '-' + e() + '-' + e() + '-' + e() + '-' + e() + e() + e();
}

async function mxStart() {
	let iframe = document.createElement('iframe');
	iframe.id = 'mxFrame';
	let chathost = 'https://dev.messengerx.io';
	let botName = 'test_chatbot';
	let pos = 'left';
	let ads = `&ads=false`;
	const wpWebsiteUrl = localStorage.getItem('wp_rest_url', window.location.origin);

	let res = undefined;
	
	try {
		res = await getJSON(`${wpWebsiteUrl}/messengerx-chatbot/v1/data`);
	} catch (error) {
		if (error.status === 404) {
			res = await getJSON(`${window.location.origin}/index.php/wp-json/messengerx-chatbot/v1/data`);
		}

	}

	if (res?.botEnabled !== 'Enable') {
		return;
	}

	botName = res?.name;
	pos = res?.pos ? res?.pos : 'right';
	iframe.src = `${chathost}/${botName}?i=t&launcherposition=${pos}${ads}`;

	const u = window.localStorage.getItem('machaaoUser') || deviceId();
	window.localStorage.setItem('machaaoUser', u);
	const i = isMobileDevice();
	if (i === false) {
		let m = `width: 170px;height: 160px;position: fixed;bottom: 0;${pos}: 0px;z-index: 2147483649;border: 0;transition: opacity 0.5s, transform 0.5s;`;
		if (pos === 'left') m += 'margin-left: 5px;';
		iframe.setAttribute('style', m);
	} else {
		const x = `width: 170px;height: 160px;position: fixed;bottom: 0;${pos}: 0;z-index: 2147483649;border: 0;transition: opacity 0.5s, transform 0.5s;`;

		iframe.setAttribute('style', x);
	}
	window.onmessage = function (event) {
		if (event.data.type && event.data.type == 'minimize') minimizeFrame();
		if (event.data.type && event.data.type == 'toggle') toggleIframeStyle();
		if (event.data.type && event.data.type == 'resetLocal') resetLocal();
		if (event.data.type && event.data.type == 'getUser') getUser();
		if (event.data.type && event.data.type == 'getLocal') getLocal();
		if (event.data.type && event.data.type == 'open') open(event.data.url);
	};

	function open(u) {
		window.location.href = u;
	}
	function minimizeFrame() {
		document.getElementById('mxFrame').style.height = '160px';
		document.getElementById('mxFrame').style.width = '170px';
	}
	function toggleIframeStyle() {
		const i = isMobileDevice();
		let iframe = document.getElementById('mxFrame');
		let s = `width: 400px;height: 660px;position: fixed;bottom: 0;${pos}: 0px;z-index: 2147483649;border: 0;transition: opacity 0.5s, transform 0.5s;`;

		if (i === false) {
			if (pos === 'left') {
				s = s + 'margin-left:10px;';
			} else if (pos === 'right') {
				s = s + 'margin-right:10px;';
			}
			iframe.setAttribute('style', s);
		} else {
			const a = `width: 100%;height: 100%;position: fixed;bottom: 0;${pos}: 0;z-index: 2147483649;border: 0;transition: opacity 0.5s, transform 0.5s;margin-bottom:0px;`;
			iframe.setAttribute('style', a);
		}
	}
	function resetLocal() {
		window.localStorage.setItem('lastMsg', null);
	}
	function getLocal() {
		const l = window.localStorage.getItem('lastMsg');
		iframe.contentWindow.postMessage({ type: 'last', value: l }, '*');
	}
	function getUser() {
		iframe.contentWindow.postMessage({ type: 'user', value: u }, '*');
	}

	document.body.appendChild(iframe);
}

mxStart();