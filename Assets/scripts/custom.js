//Removing Preloader
// setTimeout(function(){
//     var preloader = document.getElementById('preloader')
//     if(preloader){preloader.classList.add('preloader-hide');}
// },150);

document.addEventListener('DOMContentLoaded', () => {
    'use strict'

    //Global Variables
    let isPWA = true;  // Enables or disables the service worker and PWA
    let isAJAX = false; // AJAX transitions. Requires local server or server
    var pwaName = "MyTabata"; //Local Storage Names for PWA
    var pwaRemind = 1; //Days to re-remind to add to home
    var pwaNoCache = false; //Requires server and HTTPS/SSL. Will clear cache with each visit

    //Setting Service Worker Locations scope = folder | location = service worker js location
    var pwaScope = "/";
    var pwaLocation = "/_service-worker.js";

    //Place all your custom Javascript functions and plugin calls below this line
    function init_template(){
        //Caching Global Variables
        var i, e, el; //https://www.w3schools.com/js/js_performance.asp

        //Attaching Menu Hider
		// var menuHider = document.getElementsByClassName('menu-hider');
		// if(!menuHider.length){var hider = document.createElement('div'); hider.setAttribute("class", "menu-hider");document.body.insertAdjacentElement('beforebegin', hider);}
		// if(menuHider[0].classList.contains('menu-active')){menuHider[0].classList.remove('menu-active');}

        //Demo function for programtic creation of Menu
        //menu('menu-settings', 'show', 250);

        //Activating Menus
        // document.querySelectorAll('.menu').forEach(el=>{el.style.display='block'})
        // document.querySelectorAll('[data-menu-effect="menu-reveal"]').forEach(el=>{el.style.display='none'})


        //Don't jump on Empty Links
        const emptyHref = document.querySelectorAll('a[href="#"]')
        emptyHref.forEach(el => el.addEventListener('click', e => {
            e.preventDefault();
            return false;
        }));


        //To Do List
        // var toDoList = document.querySelectorAll('.todo-list a');
        // toDoList.forEach(el => el.addEventListener('click', e => {
        //     el.classList.toggle('opacity-60');
        //     if(el.querySelector('input').getAttribute('checked') == "checked"){
        //         el.querySelector('input').removeAttribute('checked');
        //     } else {
        //         el.querySelector('input').setAttribute('checked', 'checked');
        //     }
        // }));

        //Back to Top
        // function backUp(){
        //     const backToTop = document.querySelectorAll('.back-to-top-icon, .back-to-top-badge, .back-to-top');
        //     if(backToTop){
        //         backToTop.forEach(el => el.addEventListener('click',e =>{
        //             window.scrollTo({ top: 0, behavior: `smooth` })
        //         }));
        //     }
        // }

        //Check iOS Version and add min-ios15 class if higher or equal to iOS15
        function iOSversion() {
          let d, v;
          if (/iP(hone|od|ad)/.test(navigator.platform)) {
            v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
            d = {status: true, version: parseInt(v[1], 10), info: parseInt(v[1], 10)+'.'+parseInt(v[2], 10)+'.'+parseInt(v[3] || 0, 10)};
          }else{d = {status:false, version: false, info:''}}
          return d;
        }
        let iosVer = iOSversion();
        if (iosVer.version > 14) {document.querySelectorAll('#page')[0].classList.add('min-ios15');}

        //Card Extender
        const cards = document.getElementsByClassName('card');
        function card_extender(){
            var headerHeight, footerHeight, headerOnPage;
            var headerOnPage = document.querySelectorAll('.header:not(.header-transparent)')[0];

            headerOnPage ? headerHeight = document.querySelectorAll('.header')[0].offsetHeight : headerHeight = 0

            for (let i = 0; i < cards.length; i++) {
                if(cards[i].getAttribute('data-card-height') === "cover"){
                    if (window.matchMedia('(display-mode: fullscreen)').matches) {var windowHeight = window.outerHeight;}
                    if (!window.matchMedia('(display-mode: fullscreen)').matches) {var windowHeight = window.innerHeight;}
                    //Fix for iOS 15 pages with data-height="cover"
                    var coverHeight = windowHeight + 'px';
                    // - Remove this for iOS 14 issues - var coverHeight = windowHeight - headerHeight - footerHeight + 'px';
                }
                if(cards[i].getAttribute('data-card-height') === "cover-full"){
                    if (window.matchMedia('(display-mode: fullscreen)').matches) {var windowHeight = window.outerHeight;}
                    if (!window.matchMedia('(display-mode: fullscreen)').matches) {var windowHeight = window.innerHeight;}
                    var coverHeight = windowHeight + 'px';
                    cards[i].style.height =  coverHeight
                }
                if(cards[i].hasAttribute('data-card-height')){
                    var getHeight = cards[i].getAttribute('data-card-height');
                    cards[i].style.height= getHeight +'px';
                    if(getHeight === "cover"){
                        var totalHeight = getHeight
                        cards[i].style.height =  coverHeight
                    }
                }
            }
        }

        if(cards.length){
            card_extender();
            window.addEventListener("resize", card_extender);
        }

        //Dark Mode
        // function checkDarkMode(){
        //     const toggleDark = document.querySelectorAll('[data-toggle-theme]');
        //     function activateDarkMode(){
        //         document.body.classList.add('theme-dark');
        //         document.body.classList.remove('theme-light', 'detect-theme');
        //         for(let i = 0; i < toggleDark.length; i++){toggleDark[i].checked="checked"};
        //         localStorage.setItem(pwaName+'-Theme', 'dark-mode');
        //     }
        //     function activateLightMode(){
        //         document.body.classList.add('theme-light');
        //         document.body.classList.remove('theme-dark','detect-theme');
        //         for(let i = 0; i < toggleDark.length; i++){toggleDark[i].checked=false};
        //         localStorage.setItem(pwaName+'-Theme', 'light-mode');
        //     }
        //     function removeTransitions(){var falseTransitions = document.querySelectorAll('.btn, .header, .menu-box, .menu-hider, .menu-active, .page-content'); for(let i = 0; i < falseTransitions.length; i++) {falseTransitions[i].style.transition = "all 0s ease";}}
        //     function addTransitions(){var trueTransitions = document.querySelectorAll('.btn, .header, .menu-box, .menu-hider, .menu-active, .page-content'); for(let i = 0; i < trueTransitions.length; i++) {trueTransitions[i].style.transition = "";}}
        //
        //     function setColorScheme() {
        //         const isDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches
        //         const isLightMode = window.matchMedia("(prefers-color-scheme: light)").matches
        //         const isNoPreference = window.matchMedia("(prefers-color-scheme: no-preference)").matches
        //         window.matchMedia("(prefers-color-scheme: dark)").addListener(e => e.matches && activateDarkMode())
        //         window.matchMedia("(prefers-color-scheme: light)").addListener(e => e.matches && activateLightMode())
        //         if(isDarkMode) activateDarkMode();
        //         if(isLightMode) activateLightMode();
        //     }
        //
        //     //Activating Dark Mode
        //     var darkModeSwitch = document.querySelectorAll('[data-toggle-theme]')
        //     darkModeSwitch.forEach(el => el.addEventListener('click',e =>{
        //         if(document.body.className == "theme-light"){ removeTransitions(); activateDarkMode();}
        //         else if(document.body.className == "theme-dark"){ removeTransitions(); activateLightMode();}
        //         setTimeout(function(){addTransitions();},350);
        //     }));
        //
        //     //Set Color Based on Remembered Preference.
        //     if(localStorage.getItem(pwaName+'-Theme') == "dark-mode"){for(let i = 0; i < toggleDark.length; i++){toggleDark[i].checked="checked"};document.body.className = 'theme-dark';}
        //     if(localStorage.getItem(pwaName+'-Theme') == "light-mode"){document.body.className = 'theme-light';} if(document.body.className == "detect-theme"){setColorScheme();}
        //
        //     //Detect Dark/Light Mode
        //     const darkModeDetect = document.querySelectorAll('.detect-dark-mode');
        //     darkModeDetect.forEach(el => el.addEventListener('click',e =>{
        //         document.body.classList.remove('theme-light', 'theme-dark');
        //         document.body.classList.add('detect-theme')
        //         setTimeout(function(){setColorScheme();},50)
        //     }))
        // }
        // if(localStorage.getItem(pwaName+'-Theme') == "dark-mode"){document.body.className = 'theme-dark';}
        // if(localStorage.getItem(pwaName+'-Theme') == "light-mode"){document.body.className = 'theme-light';}




        //Accordion Rotate
        const accordionBtn = document.querySelectorAll('.accordion-btn');
        if(accordionBtn.length){
            accordionBtn.forEach(el => el.addEventListener('click', event => {
                el.querySelector('i:last-child').classList.toggle('fa-rotate-180');
            }));
        }

        //Link List Toggle
        var linkListToggle = document.querySelectorAll('[data-trigger-switch]:not([data-toggle-theme])');
        if(linkListToggle.length){
            linkListToggle.forEach(el => el.addEventListener('click', event => {
                var switchData = el.getAttribute('data-trigger-switch');
                var getCheck = document.getElementById(switchData);
                getCheck.checked ? getCheck.checked = false : getCheck.checked = true;
            }))
        }

        //Classic Toggle
        var classicToggle = document.querySelectorAll('.classic-toggle');
        if(classicToggle.length){
            classicToggle.forEach(el => el.addEventListener('click', event=>{
                el.querySelector('i:last-child').classList.toggle('fa-rotate-180');
                el.querySelector('i:last-child').style.transition = "all 250ms ease"
            }))
        }

        //Collapse Flip Icon
        var collapseBtn = document.querySelectorAll('[data-bs-toggle="collapse"]:not(.no-effect)');
        if(collapseBtn.length){
            collapseBtn.forEach(el => el.addEventListener('click',e =>{
                if(el.querySelectorAll('i').length){
                    el.querySelector('i').classList.toggle('fa-rotate-180')
                };
            }));
        }

        //Tabs
        var tabTrigger = document.querySelectorAll('.tab-controls a');
            if(tabTrigger.length){
            tabTrigger.forEach(function(e){
                if(e.hasAttribute('data-active')){
                    var highlightColor = e.parentNode.getAttribute('data-highlight');
                    e.classList.add(highlightColor);
                    e.classList.add('no-click');
                }
            });
            tabTrigger.forEach(el => el.addEventListener('click',e =>{
                var highlightColor = el.parentNode.getAttribute('data-highlight');
                var tabParentGroup = el.parentNode.querySelectorAll('a');
                tabParentGroup.forEach(function(e){
                    e.classList.remove(highlightColor);
                    e.classList.remove('no-click');
                });
                el.classList.add(highlightColor);
                el.classList.add('no-click');
            }));
        }

        var tabBorders = document.querySelectorAll('.tab-borders a');
            if(tabBorders.length){
            tabBorders.forEach(function(e){
                if(e.hasAttribute('data-active')){
                    var highlightColor = e.parentNode.getAttribute('data-highlight');
                    e.classList.add(highlightColor);
                    e.classList.add('border');
                    e.classList.add('no-click');
                }
            });
            tabBorders.forEach(el => el.addEventListener('click',e =>{
                var highlightColor = el.parentNode.getAttribute('data-highlight');
                var tabParentGroup = el.parentNode.querySelectorAll('a');
                tabParentGroup.forEach(function(e){
                    e.classList.remove(highlightColor);
                    e.classList.remove('border');
                    e.classList.remove('no-click');
                });
                el.classList.add(highlightColor);
                el.classList.add('border');
                el.classList.add('no-click');
            }));
        }

        // var autoActivate = document.querySelectorAll('[data-auto-activate]');
        // if(autoActivate.length){
        //     setTimeout(function(){
        //         autoActivate[0].classList.add('menu-active');
        //         menuHider[0].classList.add('menu-active');
        //     },0);
        // }

        //Copyright Year
        // function copyrightYear(){
        //     var copyrightYear = document.getElementById('copyright-year');
        //     if(copyrightYear){
        //         var dteNow = new Date();
        //         const intYear = dteNow.getFullYear();
        //         copyrightYear.textContent = intYear;
        //     }
        // }

        //Online / Offline Settings
        //Activating and Deactivating Links Based on Online / Offline State
        // function offlinePage(){
        //     var anchorsDisabled = document.querySelectorAll('a');
        //     anchorsDisabled.forEach(function(e){
        //         var hrefs = e.getAttribute('href');
        //         if(hrefs.match(/.html/)){e.classList.add('show-offline'); e.setAttribute('data-link',hrefs); e.setAttribute('href','#');}
        //     });
        //     var showOffline = document.querySelectorAll('.show-offline');
        //     showOffline.forEach(el => el.addEventListener('click', event => {
        //         document.getElementsByClassName('offline-message')[0].classList.add('offline-message-active');
        //         setTimeout(function(){document.getElementsByClassName('offline-message')[0].classList.remove('offline-message-active');},1500)
        //     }));
        // }
        // function onlinePage(){
        //     var anchorsEnabled = document.querySelectorAll('[data-link]');
        //     anchorsEnabled.forEach(function (e) {
        //         var hrefs = e.getAttribute('data-link');
        //         if (hrefs.match(/.html/)) {e.setAttribute('href', hrefs); e.removeAttribute('data-link', '');}
        //     });
        // }
        //
        // //Defining Offline/Online Variables
        // var offlineMessage = document.getElementsByClassName('offline-message')[0];
        // var onlineMessage = document.getElementsByClassName('online-message')[0];
        //
        //
        // //Online / Offine Status
        // function isOnline(){
        //     onlinePage(); onlineMessage.classList.add('online-message-active');
        //     setTimeout(function(){onlineMessage.classList.remove('online-message-active'); },2000)
        //     console.info( 'Connection: Online');
        // }
        //
        // function isOffline(){
        //     offlinePage(); offlineMessage.classList.add('offline-message-active');
        //     setTimeout(function(){offlineMessage.classList.remove('offline-message-active'); },2000)
        //     console.info( 'Connection: Offline');
        // }
        //
        // var simulateOffline = document.querySelectorAll('.simulate-offline');
        // var simulateOnline = document.querySelectorAll('.simulate-online');
        // if(simulateOffline.length){
        //     simulateOffline[0].addEventListener('click',function(){isOffline()});
        //     simulateOnline[0].addEventListener('click',function(){isOnline()});
        // }
        //
        // //Check if Online / Offline
        // function updateOnlineStatus(event) {var condition = navigator.onLine ? "online" : "offline"; isOnline(); }
        // function updateOfflineStatus(event) {isOffline();}
        // window.addEventListener('online',  updateOnlineStatus);
        // window.addEventListener('offline', updateOfflineStatus);

        //iOS Badge
        const iOSBadge = document.querySelectorAll('.simulate-iphone-badge');
        iOSBadge.forEach(el => el.addEventListener('click',e =>{
            document.getElementsByClassName('add-to-home')[0].classList.add('add-to-home-visible', 'add-to-home-ios');
            document.getElementsByClassName('add-to-home')[0].classList.remove('add-to-home-android');
        }));

        //Android Badge
        const AndroidBadge = document.querySelectorAll('.simulate-android-badge');
        AndroidBadge.forEach(el => el.addEventListener('click',e =>{
            document.getElementsByClassName('add-to-home')[0].classList.add('add-to-home-visible', 'add-to-home-android');
            document.getElementsByClassName('add-to-home')[0].classList.remove('add-to-home-ios');
        }));

        //Remove Add to Home Badge
        const addToHomeBadgeClose = document.querySelectorAll('.add-to-home');
        addToHomeBadgeClose.forEach(el => el.addEventListener('click',e =>{
            document.getElementsByClassName('add-to-home')[0].classList.remove('add-to-home-visible');
        }));

        //Detecting Mobile OS
        let isMobile = {
            Android: function() {return navigator.userAgent.match(/Android/i);},
            iOS: function() {return navigator.userAgent.match(/iPhone|iPad|iPod/i);},
            any: function() {return (isMobile.Android() || isMobile.iOS());}
        };

        const androidDev = document.getElementsByClassName('show-android');
        const iOSDev = document.getElementsByClassName('show-ios');
        const noDev = document.getElementsByClassName('show-no-device');

        if(!isMobile.any()){
            for (let i = 0; i < iOSDev.length; i++) {iOSDev[i].classList.add('disabled');}
            for (let i = 0; i < androidDev.length; i++) {androidDev[i].classList.add('disabled');}
        }
        if(isMobile.iOS()){
            document.querySelectorAll('#page')[0].classList.add('device-is-ios');
            for (let i = 0; i < noDev.length; i++) {noDev[i].classList.add('disabled');}
            for (let i = 0; i < androidDev.length; i++) {androidDev[i].classList.add('disabled');}
        }
        if(isMobile.Android()){
            document.querySelectorAll('#page')[0].classList.add('device-is-android');
            for (let i = 0; i < iOSDev.length; i++) {iOSDev[i].classList.add('disabled');}
            for (let i = 0; i < noDev.length; i++) {noDev[i].classList.add('disabled');}
        }


        //PWA Settings
        if(isPWA === true){
            var checkPWA = document.getElementsByTagName('html')[0];
            if(!checkPWA.classList.contains('isPWA')){
                if ('serviceWorker' in navigator) {
                  window.addEventListener('load', function() {
                    navigator.serviceWorker.register(pwaLocation, {scope: pwaScope}).then(function(registration){registration.update();})
                  });
                }

                //Setting Timeout Before Prompt Shows Again if Dismissed
                var hours = pwaRemind * 24; // Reset when storage is more than 24hours
                var now = Date.now();
                var setupTime = localStorage.getItem(pwaName+'-PWA-Timeout-Value');
                if (setupTime == null) {
                    localStorage.setItem(pwaName+'-PWA-Timeout-Value', now);
                } else if (now - setupTime > hours*60*60*1000) {
                    localStorage.removeItem(pwaName+'-PWA-Prompt')
                    localStorage.setItem(pwaName+'-PWA-Timeout-Value', now);
                }


                const pwaClose = document.querySelectorAll('.pwa-dismiss');
                pwaClose.forEach(el => el.addEventListener('click',e =>{
                    const pwaWindows = document.querySelectorAll('#menu-install-pwa-android, #menu-install-pwa-ios');
                    for(let i=0; i < pwaWindows.length; i++){pwaWindows[i].classList.remove('menu-active');}
                    localStorage.setItem(pwaName+'-PWA-Timeout-Value', now);
                    localStorage.setItem(pwaName+'-PWA-Prompt', 'install-rejected');
                    console.log('PWA Install Rejected. Will Show Again in '+ (pwaRemind)+' Days')
                }));

                //Trigger Install Prompt for Android
                const pwaWindows = document.querySelectorAll('#menu-install-pwa-android, #menu-install-pwa-ios');
                if(pwaWindows.length){
                    if (isMobile.Android()) {
                        if (localStorage.getItem(pwaName+'-PWA-Prompt') != "install-rejected") {
                            function showInstallPrompt() {
                                setTimeout(function(){
                                    if (!window.matchMedia('(display-mode: fullscreen)').matches) {
                                        console.log('Triggering PWA Window for Android')
                                        document.getElementById('menu-install-pwa-android').classList.add('menu-active');
                                        document.querySelectorAll('.menu-hider')[0].classList.add('menu-active');
                                    }
                                },3500);
                            }
                            var deferredPrompt;
                            window.addEventListener('beforeinstallprompt', (e) => {
                                e.preventDefault();
                                deferredPrompt = e;
                                showInstallPrompt();
                            });
                        }
                        const pwaInstall = document.querySelectorAll('.pwa-install');
                        pwaInstall.forEach(el => el.addEventListener('click', e => {
                            deferredPrompt.prompt();
                            deferredPrompt.userChoice
                                .then((choiceResult) => {
                                    if (choiceResult.outcome === 'accepted') {
                                        console.log('Added');
                                    } else {
                                        localStorage.setItem(pwaName+'-PWA-Timeout-Value', now);
                                        localStorage.setItem(pwaName+'-PWA-Prompt', 'install-rejected');
                                        setTimeout(function(){
                                            if (!window.matchMedia('(display-mode: fullscreen)').matches) {
                                                document.getElementById('menu-install-pwa-android').classList.remove('menu-active');
                                                document.querySelectorAll('.menu-hider')[0].classList.remove('menu-active');
                                            }
                                        },50);
                                    }
                                    deferredPrompt = null;
                                });
                        }));
                        window.addEventListener('appinstalled', (evt) => {
                            document.getElementById('menu-install-pwa-android').classList.remove('menu-active');
                            document.querySelectorAll('.menu-hider')[0].classList.remove('menu-active');
                        });
                    }
                    //Trigger Install Guide iOS
                    if (isMobile.iOS()) {
                        if (localStorage.getItem(pwaName+'-PWA-Prompt') != "install-rejected") {
                            setTimeout(function(){
                                if (!window.matchMedia('(display-mode: fullscreen)').matches) {
                                    console.log('Triggering PWA Window for iOS');
                                    document.getElementById('menu-install-pwa-ios').classList.add('menu-active');
                                    document.querySelectorAll('.menu-hider')[0].classList.add('menu-active');
                                }
                            },3500);
                        }
                    }
                }
            }
            checkPWA.setAttribute('class','isPWA');
        }

        //End of isPWA
        if(pwaNoCache === true){
            caches.delete('workbox-runtime').then(function() {});
            sessionStorage.clear()
            caches.keys().then(cacheNames => {
              cacheNames.forEach(cacheName => {
                caches.delete(cacheName);
              });
            });
        }

        //Lazy Loading
        var lazyLoad = new LazyLoad();

        // Check Documentation folder for detailed explanations on
        // Externally loading Javascript files for better performance.

        var plugIdent, plugClass, plugMain, plugCall;
        var plugLoc = "plugins/"

        let plugins = [];
    }

    //Fix Scroll for AJAX pages.
    // if ('scrollRestoration' in window.history) window.history.scrollRestoration = 'manual';
    //
    // //End of Init Template
    // if(isAJAX === true){
    //     if(window.location.protocol !== "file:"){
    //         const options = {
    //             containers: ["#page"],
    //             cache:false,
    //             animateHistoryBrowsing: false,
    //             plugins: [
    //                 new SwupPreloadPlugin()
    //             ],
    //             linkSelector:'a:not(.external-link):not(.default-link):not([href^="https"]):not([href^="http"]):not([data-gallery])'
    //         };
    //         const swup = new Swup(options);
    //         document.addEventListener('swup:pageView',(e) => { init_template(); })
    //     }
    // }

    init_template();
});

