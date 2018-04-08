var messagesId = 'messages';
var messagesIdWith = '#messages';
var messagesClassDiv = 'messages-content';
var messagesClassDivWith = '.messages-content';
var messagesTimeout = null;
var messagesTimeoutMillisecond = 5000;

$(function () {
    if (getMessagesContent().length > 0) {
        initMessagesTimeout();
    }
});

function initMessagesTimeout() {
    if (messagesTimeout != null) {
        clearTimeout(messagesTimeout);
        messagesTimeout = null;
    }
    
    messagesTimeout = setTimeout(function () {
        removeFirstMessage();
    }, messagesTimeoutMillisecond);
}

function removeFirstMessage() {
    var currentMessagesContent = getMessagesContent();
    
    //Si hay más de un mensaje borro el primero
    if (currentMessagesContent.length > 0) {
        currentMessagesContent.get(0).remove();
        initMessagesTimeout();
    }
}

function getMessagesContent() {
    return $(document).find(messagesIdWith + ' > ' + messagesClassDivWith);
}

function addMessagesContent(html) {
    $(document).find(messagesIdWith).append(html);
    
    if (messagesTimeout == null) {
        initMessagesTimeout();
    }
}
