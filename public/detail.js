window.onload = function () {
    var btn = document.getElementById('vote-btn');
    btn.onclick = function () {
        var params = {'id_competitive_work': this.getAttribute('data-id')};
        var message = document.createElement('div');
        $.post("/api/v1/vote", params, function (res) {
            if (res['error']) {
                message.className = 'vote error';
                message.innerHTML = res['error']['label'];
            } else if (res['success']) {
                message.className = 'vote success';
                message.innerHTML = res['success']['label'];
            }
            btn.parentNode.removeChild(btn);
            document.getElementById('buttons').appendChild(message);
        });
    }
};
