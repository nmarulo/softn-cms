var messagesId = 'messages';
var messagesClassDiv = '.messages-content';
var messagesTimeout = null;
var messagesTimeoutMillisecond = 5000;

$(function () {
    if (document.getElementById(messagesId) != null) {
        initMessagesTimeout();
    }
});

function initMessagesTimeout() {
    if (messagesTimeout != null) {
        clearTimeout(messagesTimeout);
    }
    
    messagesTimeout = setTimeout(function () {
        removeFirstMessage();
    }, messagesTimeoutMillisecond);
}

function removeFirstMessage() {
    var currentMessages = document.getElementById(messagesId);
    var currentMessagesContent = $(currentMessages).find(messagesClassDiv);
    
    //Si hay más de un mensaje borro el primero
    if (currentMessagesContent.length > 1) {
        currentMessagesContent.get(0).remove();
        initMessagesTimeout();
    } else {
        currentMessages.remove();
    }
}
