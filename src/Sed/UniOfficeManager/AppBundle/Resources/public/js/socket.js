var socket = io.connect(socket_conn);

var lineNum = 100;
var i = 101;

socket.on('response', function (msg) {
    console.log('Msg: ' + msg);
    var out = $('#terminal-output');
    var cursor = $('.terminal-cursor');
    var str = '<span class="terminal-line terminal-line-' + i + '">' + msg + '</span>';
    str = str.replace(/(?:\r\n|\r|\n)/g, '<br />');
    cursor.before(str);
    out.animate({ scrollTop: out[0].scrollHeight * 2 }, "fast");

    i++;

    var j = +i - +lineNum;
    $('.terminal-line-' + j).remove();
});

$("#terminal-window").mousemove(function(){
        $(this).draggable({
            greedy: true,
            handle: '#terminal-toolbar',

            containment: // Set containment to current viewport
                [$(document).scrollLeft(),
                    $(document).scrollTop(),
                    $(document).scrollLeft()+$(window).width()-$(this).outerWidth(),
                    $(document).scrollTop()+$(window).height()-$(this).outerHeight()]
        });
    })
    .draggable({
        scroll: false ,
        handle: '#terminal-toolbar'
    })
;
$("#terminal-body")
    .resizable({
        handles: 's',
        maxHeight: 800,
        minHeight: 350,
        start: function () {
            $("#terminal-body").css({ width: '100%' });
        },
        stop: function () {
            var to = $("#terminal-output");
            var tb = $("#terminal-body");
            var h = tb.height() - 12;
            tb.css({ width: '100%' });
            to.css({ height: h });
            to.animate({ scrollTop: ( to[0].scrollHeight * 2) }, "fast");
        }
    });

$('.terminal-green .terminal-glyph').click(function() {
    $('#terminal-body').show();
    $('#terminal-window').css({
        height: ''
    });
});
$('.terminal-yellow .terminal-glyph').click(function() {
    $('#terminal-body').hide();
    $('#terminal-window').css({
        height: ''
    });
});

$('.terminal-red .terminal-glyph').click(function() {
    $('#terminal-body').hide();
    $('#terminal-window').css({
        left: '',
        right: '',
        top: '',
        height: '',
        bottom: "0px"
    });
});
