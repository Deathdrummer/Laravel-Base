const ddrQry = {}
const methods = ['get', 'post', 'put', 'patch', 'delete', 'options', 'head'];



$.extend(ddrQry, {
	get: (url, data, params) => _action('get', url, data, params),
	post: (url, data, params) => _action('post', url, data, params),
	put: (url, data, params) => _action('put', url, data, params),
	patch: (url, data, params) => _action('patch', url, data, params),
	delete: (url, data, params) => _action('delete', url, data, params),
	options: (url, data, params) => _action('options', url, data, params),
	head: (url, data, params) => _action('head', url, data, params),
});



function _action(method, url, data = {}, params = {}) {
	
	if (url.substr(0, 1) != '/') url = '/'+url;
	
	if (_.indexOf(['get', 'head', 'options'], method) !== -1 && _hasQueryInUrl(url)) params = data;
	
	const config = _.assign({
		method: _setMethod(method),
		url,
		responseType: 'json', // 'arraybuffer', 'document', 'json', 'text', 'stream' 'blob' (только в браузере)
		headers: {},
		abortContr: null,
	}, params);
	
	if (_hasFiles(data)) config.headers['Content-Type'] = 'multipart/form-data';
	
	
	data = _setResponseType(data, config.responseType)
	
	if (['get', 'head', 'options'].includes(method)) {
		if (_hasQueryInUrl(url) == false && data) _.set(config, 'params', data);
		_.set(config, 'params._method', method);
	} else {
		data = _setMethodField(data, method);
		config['data'] = data;
	}
	
	if (config.abortContr) config['signal'] = config.abortContr?.signal;
	
	return new Promise(function(resolve, reject) {
		try {
			axios(config).then(function ({data, status, headers}) {
				let stat = data?.status || status,
					response = {};
				
				if (stat >= 200 && stat < 300) { // Успешный ответ
					response = {
						data,
						error: false,
						status: stat,
						headers
					};
					
				} else if (stat >= 400 && stat < 500) { // Ошибка клиента
					if (isDev) {
						$.notify('Ошибка клиента: '+data.full, 'error');
						console.error('Ошибка клиента: '+data.full);
					}
					
					response = {
						data: false,
						error: data,
						status: stat,
						headers
					};
					
				} else { // Другие ошибки
					if (isDev) {
						$.notify('Ошибка сервера: '+data.full, 'error');
						console.error('Ошибка сервера: '+data.full);
					}
					
					response = {
						data: false,
						error: data,
						status: stat,
						headers
					};
				}
				
				resolve(response);
				
			}).catch(err => {
				if (axios.isCancel(err)) {
					console.log('ddrQuery: запрос отменен!');
					resolve({abort: true});
				} else {
					console.log('ddrQuery: reject!');
					reject(err);
				}
			});
		} catch(e) {
			$.notify('ddrQuery try catch: ошибка загрузки', 'error');
			reject(e);
		}
	});
	
	
	// axios#get(url[, config])
	// axios#head(url[, config])
	// axios#options(url[, config])
	
	// axios#delete(url[, config])
	// axios#post(url[, data[, config]])
	// axios#put(url[, data[, config]])
	// axios#patch(url[, data[, config]])
	
	
	
}



function _setResponseType(data = null, responseType = null) {
	if (_.isNull(data) || _.isNull(responseType)) throw new Error('ddrQuery -> _setResponseType: не указаны параметры!');
	if (data instanceof FormData) data.append('_responsetype', responseType);
	else data['_responsetype'] = responseType;
	return data;
}
	


function _setMethodField(data, method = null) {
	if (_.isNull(method)) throw new Error('ddrQuery -> _setMethodField: не указан method!');
	method = method.toUpperCase();
	if (data instanceof FormData) data.append('_method', method);
	else data['_method'] = method;
	return data;
}


function _setMethod(method = null) {
	if (_.isNull(method)) throw new Error('ddrQuery -> _setMethod: не указан method!');
	return ['post', 'put', 'patch', 'delete'].includes(method) ? 'post' : 'get';
}




function _hasQueryInUrl(url = null) {
	if (_.isNull(url)) throw new Error('ddrQuery -> _hasQueryInUrl: не указан url!');
	return url.includes('?');
}



function _hasFiles(data = null) {
	if (_.isNull(data)) return false;
	let hasFiles = false;
	
	if (data instanceof FormData) {
		for (let [d, r] of data.entries()) {
			if (r instanceof File) {
				hasFiles = true;
				return false;
			}
		}
	} else {
		$.each(data, function(d, r) {
			if (r instanceof File) {
				hasFiles = true;
				return false;
			}
		});
	}
	
	return hasFiles;
}





//--------------------------------------------------------------------------------------------------



window.ddrQuery = ddrQry;