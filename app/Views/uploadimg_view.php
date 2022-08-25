<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Upload Image</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('easyui/themes/default/easyui.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('easyui/themes/icon.css') ?>">
    <script type="text/javascript" src="<?= base_url('easyui/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('easyui/jquery.easyui.min.js') ?>"></script>
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://rawgit.com/Foliotek/Croppie/master/croppie.css">
    <script type="text/javascript" src="https://rawgit.com/Foliotek/Croppie/master/croppie.js"></script>
    <script
        type="text/javascript"
        src="/js/lib/dummy.js">
    </script>
    <style id="compiled-css" type="text/css">
        #imagePreview {
            width: 180px;
            height: 180px;
            background-position: center center;
            background-size: cover;
            -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
            display: inline-block;
        }
        .upload-msg {
            text-align: center;
            padding-top: 100px;
            padding-left:30px;
            padding-right: 30px;
            font-size: 22px;
            color: #aaa;
            width: 240px;
            height: 200px;
            margin: 10px auto;
            border: 1px solid #aaa;
        }
        .upload-demo.ready #upload-demo{
            display: block;
        }
        .upload-demo.ready .buttons .upload-result, .upload-demo.ready .buttons .reset {
            display:inline;
        }
        .upload-demo #upload-demo, .upload-demo .buttons .upload-result, .upload-demo .buttons .reset, .upload-demo.ready .upload-msg {
            display: none;
        }
        #upload{
            position:absolute;
            top:-1000px;
        }
        #btn {
            background-color: #189094;
            color: white;
            padding: 10px 15px;
            border-radius: 3px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            text-shadow: none;
            cursor: pointer;
            width: auto;
            margin: 0 auto;
        }
    </style>
    <script id="insert"></script>
    <script src="/js/stringify.js?35fb45622ede3ca41784188131bba93f713641af" charset="utf-8"></script>
    <script>
        const customConsole = (w) => {
            const pushToConsole = (payload, type) => {
                w.parent.postMessage({
                    console: {
                        payload: stringify(payload),
                        type:    type
                    }
                }, "*");
            }
            w.onerror = (message, url, line, column) => {
                // the line needs to correspond with the editor panel
                // unfortunately this number needs to be altered every time this view is changed
                line = line - 70
                if (line < 0) {
                    pushToConsole(message, "error")
                } else {
                    pushToConsole(`[${line}:${column}] ${message}`, "error")
                }
            }
            let console = (function(systemConsole) {
                return {
                    log: function() {
                        let args = Array.from(arguments);
                        pushToConsole(args, "log");
                        systemConsole.log.apply(this, args);
                    },
                    info: function() {
                        let args = Array.from(arguments);
                        pushToConsole(args, "info");
                        systemConsole.info.apply(this, args);
                    },
                    warn: function() {
                        let args = Array.from(arguments);
                        pushToConsole(args, "warn");
                        systemConsole.warn.apply(this, args);
                    },
                    error: function() {
                        let args = Array.from(arguments);
                        pushToConsole(args, "error");
                        systemConsole.error.apply(this, args);
                    },
                    system: function(arg) {
                        pushToConsole(arg, "system");
                    },
                    clear: function() {
                        systemConsole.clear.apply(this, {});
                    },
                    time: function() {
                        let args = Array.from(arguments);
                        systemConsole.time.apply(this, args);
                    },
                    assert: function(assertion, label) {
                        if (!assertion) {
                            pushToConsole(label, "log");
                        }
                        let args = Array.from(arguments);
                        systemConsole.assert.apply(this, args);
                    }
                }
            } (window.console));
            window.console = { ...window.console, ...console }
            console.system("Running fiddle");
        }
        if (window.parent) {
            customConsole(window);
        }
    </script>
</head>
<body>
    <div class="upload-demo">
        <input type="file" id="upload" value="Choose a file" accept="image/*" data-role="none"/>
        <div class="col-1-2" style="margin: 0 auto;display:table;">
            <div class="upload-msg">
                Click to upload an image
            </div>
            <div id="upload-demo"></div>
            <div class="buttons" style="margin:0 auto;display:table;">
                <button id="btn" class="upload-result" data-role="none">Use This Image</button>
                <button id="btn" class="reset" data-role="none" style="margin-left:5px;">Remove Image</button>
            </div>
            <div id="upload-progress"></div>
        </div>
    </div>
    <script type="text/javascript">//<![CDATA[
        $(function() {
            $(".upload-msg").click(function() {
                $("#upload").click();
            });
            ////this works but wont let me set a new readFile input
		    $(".reset").click(function () {
                $('.upload-demo').removeClass('ready');
                $('#upload').val(''); // this will clear the input val.
                $uploadCrop.croppie('bind', {
                    url : ''
                }).then(function () {
                    console.log('reset complete');
                });
            });
			function popupResult(result) {
	    		var html;
                if (result.html) {
                    html = result.html;
                }
                if (result.src) {
                    html = '<img src="' + result.src + '" />';
                }
                $("#result").html(html);
            }
	   		var $uploadCrop;
            function readFile(input) {
                if (input.files && input.files[0]) {
                    if (/^image/.test( input.files[0].type)) { // only image file
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('.upload-demo').addClass('ready');
                            $uploadCrop.croppie('bind', {
                                url: e.target.result
                            }).then(function() {
                                console.log('jQuery bind complete');
                            });
                            
                        }
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        alert("You may only select image files");
                    }
                } else {
                    alert("Sorry - you're browser doesn't support the FileReader API");
                }
            }
    		$uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 146,
                    height: 146,
                    type: 'square'
                },
                boundary: {
                    width: 300,
                    height: 300
                },
            });
            $('#upload').on('change', function () { readFile(this); });
            $('.upload-result').on('click', function (ev) {
                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp) {
                    popupResult({
                        src: resp
                    });
                    $('#upload-progress').html('<img src="https://localhost/rhomes/public/images/'+
                        'progress-bar-gif-transparent-3.gif" />');
                    $.ajax({
                        url: getRestAPI('uploadimg/upload'),
                        type: 'POST',
                        data: {imagebase64: resp},
                        success:function(data)
                        {
                            setTimeout(function() {
                                $('#upload-progress').html('');
                                console.log(data);
                            }, 1000);
                        }
                    });
                });
            });
        });
    //]]></script>

    <script>
        // tell the embed parent frame the height of the content
        if (window.parent && window.parent.parent) {
            window.parent.parent.postMessage(["resultsFrame", {
            height: document.body.getBoundingClientRect().height,
            slug: ""
            }], "*")
        }
        // always overwrite window.name, in case users try to set it manually
        window.name = "result"
    </script>

    <script>
        let allLines = [];
        window.addEventListener("message", (message) => {
            if (message.data.console) {
                let insert = document.querySelector("#insert");
                allLines.push(message.data.console.payload);
                insert.innerHTML = allLines.join(";\r");
                let result = eval.call(null, message.data.console.payload);
                if (result !== undefined) {
                    console.log(result);
                }
            }
        })
    </script>
</body>
</html>