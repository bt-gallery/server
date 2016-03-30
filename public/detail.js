window.onload = function () {
    document.getElementById('vote-btn').onclick = function () {
        var params = {'id_competitive_work': this.getAttribute('data-id')},
        message = document.createElement('div'),
        xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xhr.open('POST', '/api/v1/vote');
        xhr.send(JSON.stringify(params));
        if (xhr.status != 200) {
            message.className = 'vote error';
            message.innerHTML = 'Ошибка! Попробуйте позднее.';
            console.log(xhr.status + ': ' + xhr.statusText);
        } else {
            if (xhr.responseText['error']) {
                message.className = 'vote error';
                message.innerHTML = xhr.responseText['error']['label'];
            } else if (xhr.responseText['success']){
                message.className = 'vote success';
                message.innerHTML =  xhr.responseText['success']['label'];
            }
        }
        this.parentNode.removeChild(this);
        document.getElementById('buttons').appendChild(message);
    };
};
