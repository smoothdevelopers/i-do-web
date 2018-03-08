$(function (){

    var rythm = new Rythm();
    rythm.addRythm('pulse1','pulse',0,10)
    rythm.addRythm('pulse2','pulse',0,10, { min: 0.1, max: 1 })
    rythm.addRythm('pulse3','pulse',0,10, { min: 1, max: 1.75 })
    rythm.addRythm('shake1','shake',0,10)
    rythm.addRythm('shake2','shake',0,10, { min: 0, max: 20 })
    rythm.addRythm('shake3','shake',0,10, { direction: 'left' })
    rythm.addRythm('twist1','twist',0,10)
    rythm.addRythm('twist2','twist',0,10, { min: 20, max: 180 })
    rythm.addRythm('twist3','twist',0,10, { direction: 'left' })


    $('#fa-egg').click(function() {

        var elem = document.getElementsByTagName('html')[0];
        if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
            if (elem.requestFullScreen) {
                elem.requestFullScreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullScreen) {
                elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        }

        $('html').animate({scrollTop: 0}, 'slow');
        $('.sidebar-toggle').click();

        if(rythm.stopped === false){
          rythm.stop();
        }
        rythm.setMusic("../c.mp3");
        rythm.setGain(0.1);
        rythm.start();
    });
});
