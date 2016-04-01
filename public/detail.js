window.onload = function () {
    document.getElementById('vote-btn').onclick = function () {
        var params = {'id_competitive_work': this.getAttribute('data-id')},
            message = document.createElement('div'),
            xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"),
            that = this;
        xhr.open('POST', '/api/v1/vote');
        xhr.send(JSON.stringify(params));
        xhr.onreadystatechange = function () {
            if (this.readyState != 4)
                return;
            else
                var response = JSON.parse(xhr.responseText);
            if (xhr.status != 200) {
                message.className = 'vote error';
                message.innerHTML = 'Ошибка! Попробуйте позднее.';
            } else {
                if (response['error']) {
                    message.className = 'vote error';
                    message.innerHTML = response['error']['label'];
                } else if (response['success']) {
                    var counter = document.getElementById('vote-count');
                    counter.innerHTML = ++counter.innerHTML;
                    message.className = 'vote success';
                    message.innerHTML = response['success']['label'];
                }
            }
            that.parentNode.removeChild(that);
            document.getElementById('buttons').appendChild(message);

        }
    }
};
