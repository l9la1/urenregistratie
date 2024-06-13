var timeout;
// This is for a default api call
window.api = function (url, method, data = null, timeout = 2000, callback = null) {
    var xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.withCredentials = true;
    if (timeout) {
        clearTimeout(timeout);
        $('#message').hide();
    }
    xhr.onreadystatechange = function () {
        if (this.responseText) {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(this.responseText);
                var out = $('#message');
                out.empty();
                if (response.success) {
                    out.removeClass("alert-danger");
                    out.addClass("alert-success");
                    out.addClass("text-center");
                    out.text(response.success);
                    out.show();
                    out.css("z-index", "100");
                } else {
                    out.removeClass("alert-success");
                    out.removeClass("text-center");
                    out.addClass("alert-danger");
                    var ul = $("<ul style='margin-left:50%; transform:translate(-25%,50%);'>");
                    for (var i = 0; i < response.error.length; i++) {
                        ul.append("<li>" + response.error[i] + "</li>");
                    }
                    ul.append("</ul>");
                    out.append(ul);
                    out.css("z-index", "100");

                    out.show();
                }
                timeout = setTimeout(function () { //wait and hide alert
                    out.fadeOut('slow', 'linear', function () {
                        out.css("z-index", "-1");
                        if (callback != null)
                            callback();
                    });
                }, timeout);
            }
        }
    };
    if (data != null)
        xhr.send(data);
    else
        xhr.send();
}

window.onload = function () {
    setInputVal();
}

// Set all the input date values to 00-00-0000
function setInputVal() {
    var now = new Date();

    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);

    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    var el = $("input");
    for (var i = 0; i < el.length; i++) {
        if (el[i].type == 'date' && $(el[i]).val() == "")
            $(el[i]).val(today);
    }
}

window.translateDate = function () {
    // Get all the elements that can contain a date
    var links = $("div .form-control, .card-title h1");
    // The translation data
    var data = {
        'monday': "maandag",
        'tuesday': "dinsdag",
        'wednesday': "woensdag",
        'thursday': "donderdag",
        'friday': "vrijdag",
        'saturday': "zaterdag",
        'sunday': "zondag",
        'january': "januari",
        'february': "februari",
        'march': "maart",
        'april': "april",
        'may': "mei",
        'june': "juni",
        'july': "juli",
        'august': "augustus",
        'september': "september",
        'october': "oktober",
        'november': "november",
        'december': "december",
    }
    // Loop over each element in links
    links.each(function () {
        // Loop over each key in data
        for (var key in data) {
            $(this).text($(this).text().replace(new RegExp(key, 'gi'), data[key]));
        }
    });
    setInputVal();

}