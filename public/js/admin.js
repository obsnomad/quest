var adminValues = $.cookie('adminValues') ? JSON.parse($.cookie('adminValues')) : {};
adminValues = $.extend({
    menuCollapsed: false,
    gameActiveTab: 0,
    gameTableMode: 'show',
}, adminValues);
$.cookie.raw = true;

function getAdminValue(key) {
    return adminValues[key];
}

function setAdminValue(key, value) {
    adminValues[key] = value;
    $.cookie('adminValues', JSON.stringify(adminValues));
}

function getStorageValue(key, json = true) {
    var data = localStorage.getItem(key);
    if (json) {
        data = data ? JSON.parse(data) : null;
    }
    return data;
}

function setStorageValue(key, value, json = true) {
    if (json) {
        value = JSON.stringify(value);
    }
    localStorage.setItem(key, value);
}

$('body')
    .on('expanded.pushMenu', function () {
        setAdminValue('menuCollapsed', false);
    })
    .on('collapsed.pushMenu', function () {
        setAdminValue('menuCollapsed', true);
    });

function array_unique(arr, key) {
    var tmp_arr = [];
    var tmp_arr_key = [];
    for (var i = 0; i < arr.length; i++) {
        if (tmp_arr_key.indexOf(key ? arr[i][key] : arr[i]) === -1) {
            tmp_arr.push(arr[i]);
            tmp_arr_key.push(arr[i][key]);
        }
    }
    return tmp_arr;
}