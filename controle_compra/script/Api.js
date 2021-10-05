const Api = {
    host: 'http://localhost:8000/controle_equipamentos/conecta.php',
    post: (params) => {
        let bodyRequest = JSON.stringify(params)
        let configRequest = {
            method: 'POST',
            cache: 'default',
            body: bodyRequest,
            headers: {
                'Content-Type': 'application/json'
            }
        }
        let myRequest = new Request(Api.host, configRequest);

        return fetch(myRequest).then(function (response) {
            return response.json();
        })
    },
    upload: (form_data) => {
        let configRequest = {
            method: 'POST',
            cache: 'default',
            body: form_data
        }
        let myRequest = new Request(Api.host, configRequest);

        return fetch(myRequest).then(function (response) {
            return response.json();
        })
    }

}