<script>
  window.Capacitor = { DEBUG: true };

  //# sourceURL=capacitor-runtime.js

  (function(win) {
    win.Capacitor = win.Capacitor || {
      Plugins: {}
    };

    var capacitor = Capacitor;

    // Export Cordova if not defined
    win.cordova = win.cordova || {};

    // Add any legacy handlers to keep Cordova compat 100% good
    addLegacyHandlers(win);

    capacitor.Plugins = capacitor.Plugins || {};

    capacitor.DEBUG = typeof capacitor.DEBUG === 'undefined' ? true : capacitor.DEBUG;

    // keep a collection of callbacks for native response data
    var calls = {};

    // Counter of callback ids, randomized to avoid
    // any issues during reloads if a call comes back with
    // an existing callback id from an old session
    var callbackIdCount = Math.floor(Math.random() * 134217728);

    var lastError = null;
    var errorModal = null;

    // create the postToNative() fn if needed
    if (win.androidBridge) {
      // android platform
      postToNative = function androidBridge(data) {
        win.androidBridge.postMessage(JSON.stringify(data));
      };
      capacitor.isNative = true;
      capacitor.isAndroid = true;
      capacitor.platform = 'android';

    } else if (win.webkit && win.webkit.messageHandlers && win.webkit.messageHandlers.bridge) {
      // ios platform
      postToNative = function iosBridge(data) {
        data.type = 'message';
        win.webkit.messageHandlers.bridge.postMessage(data);
      };
      capacitor.isNative = true;
      capacitor.isIOS = true;
      capacitor.platform = 'ios';
    }

    var useFallbackLogging = Object.keys(win.console).length === 0;
    if(useFallbackLogging) {
      win.console.warn('Advance console logging disabled.')
    }

    // patch window.console on iOS and store original console fns
    var orgConsole = capacitor.isIOS ? {} : win.console;

    // list log functions bridged to native log
    var bridgedLevels = {
      debug: true,
      error: true,
      info: true,
      log: true,
      trace: true,
      warn: true,
    };
    if (capacitor.isIOS) {
      Object.keys(win.console).forEach(function (level) {
        if (typeof win.console[level] === 'function') {
          // loop through all the console functions and keep references to the original
          orgConsole[level] = win.console[level];
          win.console[level] = function capacitorConsole() {
            var msgs = Array.prototype.slice.call(arguments);

            // console log to browser
            orgConsole[level].apply(win.console, msgs);

            if (capacitor.isNative && bridgedLevels[level]) {
              // send log to native to print
              try {
                // convert all args to strings
                msgs = msgs.map(function (arg) {
                  if (typeof arg === 'object') {
                    try {
                      arg = JSON.stringify(arg);
                    } catch (e) {}
                  }
                  // convert to string
                  return arg + '';
                });
                capacitor.toNative('Console', 'log', {
                  level: level,
                  message: msgs.join(' ')
                });
              } catch (e) {
                // error converting/posting console messages
                orgConsole.error.apply(win.console, e);
              }
            }
          };
        }
      });
    }

    function addLegacyHandlers(win) {
      win.navigator.app = {
        exitApp: function() {
          capacitor.toNative("App", "exitApp", {}, null);
        }
      }
      var documentAddEventListener = document.addEventListener;
      document.addEventListener = function() {
        var name = arguments[0];
        var handler = arguments[1];
        if (name === 'deviceready') {
          setTimeout(function() {
            handler && handler();
          });
        } else if (name === 'backbutton') {
          // Add a dummy listener so Capacitor doesn't do the default
          // back button action
          Capacitor.Plugins.App && Capacitor.Plugins.App.addListener('backButton', function() {});
        }
        return documentAddEventListener.apply(document, arguments);
      }
    }

    /*
     * Check if a Plugin is available
     */
    capacitor.isPluginAvailable = function isPluginAvailable(name) {
      return this.Plugins.hasOwnProperty(name);
    }

    capacitor.convertFileSrc = function convertFileSrc(url) {
      if (!url) {
        return url;
      }
      if (url.startsWith('/')) {
        return window.WEBVIEW_SERVER_URL + '/_capacitor_file_' + url;
      }
      if (url.startsWith('file://')) {
        return window.WEBVIEW_SERVER_URL + url.replace('file://', '/_capacitor_file_');
      }
      if (url.startsWith('content://')) {
        return window.WEBVIEW_SERVER_URL + url.replace('content:/', '/_capacitor_content_');
      }
      return url;
    }

    /*
     * Check running platform
     */
    capacitor.getPlatform = function getPlatform() {
      return this.platform;
    }

    /**
     * Send a plugin method call to the native layer
     */
    capacitor.toNative = function toNative(pluginId, methodName, options, storedCallback) {
      try {
        if (capacitor.isNative) {
          var callbackId = '-1';

          if (storedCallback && (typeof storedCallback.callback === 'function' || typeof storedCallback.resolve === 'function')) {
            // store the call for later lookup
            callbackId = ++callbackIdCount + '';
            calls[callbackId] = storedCallback;
          }

          var call = {
            callbackId: callbackId,
            pluginId: pluginId,
            methodName: methodName,
            options: options || {}
          };

          if (capacitor.DEBUG) {
            if (pluginId !== 'Console') {
              capacitor.logToNative(call);
            }
          }

          // post the call data to native
          postToNative(call);

          return callbackId;

        } else {
          orgConsole.warn.call(win.console, 'browser implementation unavailable for: ' + pluginId);
        }

      } catch (e) {
        orgConsole.error.call(win.console, e);
      }

      return null;
    };

    /**
     * Process a response from the native layer.
     */
    capacitor.fromNative = function fromNative(result) {
      if (capacitor.DEBUG) {
        if (result.pluginId !== 'Console') {
          capacitor.logFromNative(result);
        }
      }
      // get the stored call, if it exists
      try {
        var storedCall = calls[result.callbackId];

        if (storedCall) {
          // looks like we've got a stored call

          if (result.error && typeof result.error === 'object') {
            // ensure stacktraces by copying error properties to an Error
            result.error = Object.keys(result.error).reduce(function(err, key) {
              err[key] = result.error[key];
              return err;
            }, new Error());
          }

          if (typeof storedCall.callback === 'function') {
            // callback
            if (result.success) {
              storedCall.callback(result.data);
            } else {
              storedCall.callback(null, result.error);
            }

          } else if (typeof storedCall.resolve === 'function') {
            // promise
            if (result.success) {
              storedCall.resolve(result.data);
            } else {
              storedCall.reject(result.error);
            }

            // no need to keep this stored callback
            // around for a one time resolve promise
            delete calls[result.callbackId];
          }

        } else if (!result.success && result.error) {
          // no stored callback, but if there was an error let's log it
          orgConsole.warn.call(win.console, result.error);
        }

        if (result.save === false) {
          delete calls[result.callbackId];
        }

      } catch (e) {
        orgConsole.error.call(win.console, e);
      }

      // always delete to prevent memory leaks
      // overkill but we're not sure what apps will do with this data
      delete result.data;
      delete result.error;
    };

    capacitor.logJs = function(message, level) {
      switch (level) {
        case 'error':
          console.error(message);
          break;
        case 'warn':
          console.warn(message);
          break;
        case 'info':
          console.info(message);
          break;
        default:
          console.log(message);
      }
    }

    capacitor.withPlugin = function withPlugin(_pluginId, _fn) {
    };

    capacitor.nativeCallback = function (pluginId, methodName, options, callback) {
      if(typeof options === 'function') {
        callback = options;
        options = null;
      }
      return capacitor.toNative(pluginId, methodName, options, {
        callback: callback
      });
    };

    capacitor.nativePromise = function (pluginId, methodName, options) {
      return new Promise(function (resolve, reject) {
        capacitor.toNative(pluginId, methodName, options, {
          resolve: resolve,
          reject: reject
        });
      });
    };


    capacitor.addListener = function(pluginId, eventName, callback) {
      var callbackId = capacitor.nativeCallback(pluginId, 'addListener', {
        eventName: eventName
      }, callback);
      return {
        remove: function() {
          console.log('Removing listener', pluginId, eventName);
          capacitor.removeListener(pluginId, callbackId, eventName, callback);
        }
      }
    };

    capacitor.removeListener = function(pluginId, callbackId, eventName, callback) {
      capacitor.nativeCallback(pluginId, 'removeListener', {
        callbackId: callbackId,
        eventName: eventName
      }, callback);
    }

    capacitor.createEvent = function(type, data) {
      var event = document.createEvent('Events');
      event.initEvent(type, false, false);
      if (data) {
        for (var i in data) {
          if (data.hasOwnProperty(i)) {
            event[i] = data[i];
          }
        }
      }
      return event;
    }

    capacitor.triggerEvent = function(eventName, target, data) {
      var eventData = data || {};
      var event = this.createEvent(eventName, eventData);
      if (target === "document") {
        if (cordova.fireDocumentEvent) {
          cordova.fireDocumentEvent(eventName, eventData);
        } else {
          document.dispatchEvent(event);
        }
      } else if (target === "window") {
        window.dispatchEvent(event);
      } else {
        var targetEl = document.querySelector(target);
        targetEl && targetEl.dispatchEvent(event);
      }
    }

    capacitor.handleError = function(error) {
      console.error(error);
    }

    capacitor.handleWindowError = function (msg, url, lineNo, columnNo, error) {
      var string = msg.toLowerCase();
      var substring = "script error";
      if (string.indexOf(substring) > -1) {
        // Some IE issue?
      } else {
        var errObj = {
          type: 'js.error',
          error: {
            message: msg,
            url: url,
            line: lineNo,
            col: columnNo,
            errorObject: JSON.stringify(error)
          }
        };
        if (error !== null) {
          win.Capacitor.handleError(error);
        }
        if(capacitor.isAndroid) {
          win.androidBridge.postMessage(JSON.stringify(errObj));
        } else if(capacitor.isIOS) {
          win.webkit.messageHandlers.bridge.postMessage(errObj);
        }
      }

      return false;
    };

    capacitor.logToNative = function(call) {
      if(!useFallbackLogging) {
        var c = orgConsole;
        c.groupCollapsed('%cnative %c' + call.pluginId + '.' + call.methodName + ' (#' + call.callbackId + ')', 'font-weight: lighter; color: gray', 'font-weight: bold; color: #000');
        c.dir(call);
        c.groupEnd();
      } else {
        win.console.log('LOG TO NATIVE: ', call);
        if (capacitor.isIOS) {
          try {
            capacitor.toNative('Console', 'log', {message: JSON.stringify(call)});
          } catch (e) {
            win.console.log('Error converting/posting console messages');
          }
        }
      }
    }

    capacitor.logFromNative = function(result) {
      if(!useFallbackLogging) {
        var c = orgConsole;

        var success = result.success === true;

        var tagStyles = success ? 'font-style: italic; font-weight: lighter; color: gray' :
          'font-style: italic; font-weight: lighter; color: red';

        c.groupCollapsed('%cresult %c' + result.pluginId + '.' + result.methodName + ' (#' + result.callbackId + ')',
          tagStyles,
          'font-style: italic; font-weight: bold; color: #444');
        if (result.success === false) {
          c.error(result.error);
        } else {
          c.dir(result.data);
        }
        c.groupEnd();
      } else {
        if (result.success === false) {
          win.console.error(result.error);
        } else {
          win.console.log(result.data);
        }
      }
    }

    capacitor.uuidv4 = function() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    }

    if (Capacitor.DEBUG) {
      window.onerror = capacitor.handleWindowError;
    }

    win.Ionic = win.Ionic || {};
    win.Ionic.WebView = win.Ionic.WebView || {};

    win.Ionic.WebView.getServerBasePath = function(callback) {
      Capacitor.Plugins.WebView.getServerBasePath().then(function(result) {
        callback(result.path);
      });
    }

    win.Ionic.WebView.setServerBasePath = function (path) {
      Capacitor.Plugins.WebView.setServerBasePath({"path": path});
    }

    win.Ionic.WebView.persistServerBasePath = function () {
      Capacitor.Plugins.WebView.persistServerBasePath();
    }

    win.Ionic.WebView.convertFileSrc = function(url) {
      return Capacitor.convertFileSrc(url);
    }

  })(window);



  // Begin: Capacitor Plugin JS
  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['App'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('App', eventName, callback);
    }
    t['openUrl'] = function(_options) {
      return w.Capacitor.nativePromise('App', 'openUrl', _options)
    }
    t['getState'] = function(_options) {
      return w.Capacitor.nativePromise('App', 'getState', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('App', 'removeAllListeners', _options)
    }
    t['canOpenUrl'] = function(_options) {
      return w.Capacitor.nativePromise('App', 'canOpenUrl', _options)
    }
    t['getLaunchUrl'] = function(_options) {
      return w.Capacitor.nativePromise('App', 'getLaunchUrl', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('App', 'requestPermissions', _options)
    }
    t['exitApp'] = function(_options) {
      return w.Capacitor.nativePromise('App', 'exitApp', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Accessibility'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Accessibility', eventName, callback);
    }
    t['isScreenReaderEnabled'] = function(_options) {
      return w.Capacitor.nativePromise('Accessibility', 'isScreenReaderEnabled', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Accessibility', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Accessibility', 'requestPermissions', _options)
    }
    t['speak'] = function(_options) {
      return w.Capacitor.nativePromise('Accessibility', 'speak', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Geolocation'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Geolocation', eventName, callback);
    }
    t['watchPosition'] = function(_options, _callback) {
      return w.Capacitor.nativeCallback('Geolocation', 'watchPosition', _options, _callback)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Geolocation', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Geolocation', 'requestPermissions', _options)
    }
    t['getCurrentPosition'] = function(_options) {
      return w.Capacitor.nativePromise('Geolocation', 'getCurrentPosition', _options)
    }
    t['clearWatch'] = function(_options) {
      return w.Capacitor.nativePromise('Geolocation', 'clearWatch', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Device'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Device', eventName, callback);
    }
    t['getLanguageCode'] = function(_options) {
      return w.Capacitor.nativePromise('Device', 'getLanguageCode', _options)
    }
    t['getInfo'] = function(_options) {
      return w.Capacitor.nativePromise('Device', 'getInfo', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Device', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Device', 'requestPermissions', _options)
    }
    t['getBatteryInfo'] = function(_options) {
      return w.Capacitor.nativePromise('Device', 'getBatteryInfo', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Keyboard'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Keyboard', eventName, callback);
    }
    t['setAccessoryBarVisible'] = function(_options) {
      return w.Capacitor.nativePromise('Keyboard', 'setAccessoryBarVisible', _options)
    }
    t['hide'] = function(_options) {
      return w.Capacitor.nativePromise('Keyboard', 'hide', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Keyboard', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Keyboard', 'requestPermissions', _options)
    }
    t['setResizeMode'] = function(_options) {
      return w.Capacitor.nativePromise('Keyboard', 'setResizeMode', _options)
    }
    t['show'] = function(_options) {
      return w.Capacitor.nativePromise('Keyboard', 'show', _options)
    }
    t['setStyle'] = function(_options) {
      return w.Capacitor.nativePromise('Keyboard', 'setStyle', _options)
    }
    t['setScroll'] = function(_options) {
      return w.Capacitor.nativePromise('Keyboard', 'setScroll', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['StatusBar'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('StatusBar', eventName, callback);
    }
    t['hide'] = function(_options) {
      return w.Capacitor.nativePromise('StatusBar', 'hide', _options)
    }
    t['setBackgroundColor'] = function(_options) {
      return w.Capacitor.nativePromise('StatusBar', 'setBackgroundColor', _options)
    }
    t['setOverlaysWebView'] = function(_options) {
      return w.Capacitor.nativePromise('StatusBar', 'setOverlaysWebView', _options)
    }
    t['getInfo'] = function(_options) {
      return w.Capacitor.nativePromise('StatusBar', 'getInfo', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('StatusBar', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('StatusBar', 'requestPermissions', _options)
    }
    t['show'] = function(_options) {
      return w.Capacitor.nativePromise('StatusBar', 'show', _options)
    }
    t['setStyle'] = function(_options) {
      return w.Capacitor.nativePromise('StatusBar', 'setStyle', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['PushNotifications'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('PushNotifications', eventName, callback);
    }
    t['requestPermission'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'requestPermission', _options)
    }
    t['listChannels'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'listChannels', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('PushNotifications', 'removeAllListeners', _options)
    }
    t['removeDeliveredNotifications'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'removeDeliveredNotifications', _options)
    }
    t['createChannel'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'createChannel', _options)
    }
    t['getDeliveredNotifications'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'getDeliveredNotifications', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'requestPermissions', _options)
    }
    t['removeAllDeliveredNotifications'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'removeAllDeliveredNotifications', _options)
    }
    t['deleteChannel'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'deleteChannel', _options)
    }
    t['register'] = function(_options) {
      return w.Capacitor.nativePromise('PushNotifications', 'register', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['BackgroundTask'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('BackgroundTask', eventName, callback);
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('BackgroundTask', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('BackgroundTask', 'requestPermissions', _options)
    }
    t['finish'] = function(_options) {
      return w.Capacitor.nativePromise('BackgroundTask', 'finish', _options)
    }
    t['beforeExit'] = function(_options, _callback) {
      return w.Capacitor.nativeCallback('BackgroundTask', 'beforeExit', _options, _callback)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Photos'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Photos', eventName, callback);
    }
    t['getPhotos'] = function(_options) {
      return w.Capacitor.nativePromise('Photos', 'getPhotos', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Photos', 'removeAllListeners', _options)
    }
    t['savePhoto'] = function(_options) {
      return w.Capacitor.nativePromise('Photos', 'savePhoto', _options)
    }
    t['getAlbums'] = function(_options) {
      return w.Capacitor.nativePromise('Photos', 'getAlbums', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Photos', 'requestPermissions', _options)
    }
    t['createAlbum'] = function(_options) {
      return w.Capacitor.nativePromise('Photos', 'createAlbum', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Storage'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Storage', eventName, callback);
    }
    t['set'] = function(_options) {
      return w.Capacitor.nativePromise('Storage', 'set', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Storage', 'removeAllListeners', _options)
    }
    t['keys'] = function(_options) {
      return w.Capacitor.nativePromise('Storage', 'keys', _options)
    }
    t['get'] = function(_options) {
      return w.Capacitor.nativePromise('Storage', 'get', _options)
    }
    t['clear'] = function(_options) {
      return w.Capacitor.nativePromise('Storage', 'clear', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Storage', 'requestPermissions', _options)
    }
    t['remove'] = function(_options) {
      return w.Capacitor.nativePromise('Storage', 'remove', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['LocalNotifications'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('LocalNotifications', eventName, callback);
    }
    t['cancel'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'cancel', _options)
    }
    t['areEnabled'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'areEnabled', _options)
    }
    t['requestPermission'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'requestPermission', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('LocalNotifications', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'requestPermissions', _options)
    }
    t['deleteChannel'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'deleteChannel', _options)
    }
    t['registerActionTypes'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'registerActionTypes', _options)
    }
    t['schedule'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'schedule', _options)
    }
    t['listChannels'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'listChannels', _options)
    }
    t['createChannel'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'createChannel', _options)
    }
    t['getPending'] = function(_options) {
      return w.Capacitor.nativePromise('LocalNotifications', 'getPending', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Toast'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Toast', eventName, callback);
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Toast', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Toast', 'requestPermissions', _options)
    }
    t['show'] = function(_options) {
      return w.Capacitor.nativePromise('Toast', 'show', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Modals'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Modals', eventName, callback);
    }
    t['confirm'] = function(_options) {
      return w.Capacitor.nativePromise('Modals', 'confirm', _options)
    }
    t['showActions'] = function(_options) {
      return w.Capacitor.nativePromise('Modals', 'showActions', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Modals', 'removeAllListeners', _options)
    }
    t['alert'] = function(_options) {
      return w.Capacitor.nativePromise('Modals', 'alert', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Modals', 'requestPermissions', _options)
    }
    t['prompt'] = function(_options) {
      return w.Capacitor.nativePromise('Modals', 'prompt', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Network'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Network', eventName, callback);
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Network', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Network', 'requestPermissions', _options)
    }
    t['getStatus'] = function(_options) {
      return w.Capacitor.nativePromise('Network', 'getStatus', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Permissions'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Permissions', eventName, callback);
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Permissions', 'removeAllListeners', _options)
    }
    t['query'] = function(_options) {
      return w.Capacitor.nativePromise('Permissions', 'query', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Permissions', 'requestPermissions', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['SplashScreen'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('SplashScreen', eventName, callback);
    }
    t['hide'] = function(_options) {
      return w.Capacitor.nativePromise('SplashScreen', 'hide', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('SplashScreen', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('SplashScreen', 'requestPermissions', _options)
    }
    t['show'] = function(_options) {
      return w.Capacitor.nativePromise('SplashScreen', 'show', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Camera'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Camera', eventName, callback);
    }
    t['getPhoto'] = function(_options) {
      return w.Capacitor.nativePromise('Camera', 'getPhoto', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Camera', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Camera', 'requestPermissions', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Clipboard'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Clipboard', eventName, callback);
    }
    t['read'] = function(_options) {
      return w.Capacitor.nativePromise('Clipboard', 'read', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Clipboard', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Clipboard', 'requestPermissions', _options)
    }
    t['write'] = function(_options) {
      return w.Capacitor.nativePromise('Clipboard', 'write', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Haptics'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Haptics', eventName, callback);
    }
    t['notification'] = function(_options) {
      return w.Capacitor.nativePromise('Haptics', 'notification', _options)
    }
    t['selectionStart'] = function(_options) {
      return w.Capacitor.nativePromise('Haptics', 'selectionStart', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Haptics', 'removeAllListeners', _options)
    }
    t['selectionChanged'] = function(_options) {
      return w.Capacitor.nativePromise('Haptics', 'selectionChanged', _options)
    }
    t['impact'] = function(_options) {
      return w.Capacitor.nativePromise('Haptics', 'impact', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Haptics', 'requestPermissions', _options)
    }
    t['selectionEnd'] = function(_options) {
      return w.Capacitor.nativePromise('Haptics', 'selectionEnd', _options)
    }
    t['vibrate'] = function(_options) {
      return w.Capacitor.nativePromise('Haptics', 'vibrate', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Filesystem'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Filesystem', eventName, callback);
    }
    t['deleteFile'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'deleteFile', _options)
    }
    t['stat'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'stat', _options)
    }
    t['appendFile'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'appendFile', _options)
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Filesystem', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'requestPermissions', _options)
    }
    t['rmdir'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'rmdir', _options)
    }
    t['readdir'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'readdir', _options)
    }
    t['rename'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'rename', _options)
    }
    t['readFile'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'readFile', _options)
    }
    t['copy'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'copy', _options)
    }
    t['mkdir'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'mkdir', _options)
    }
    t['writeFile'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'writeFile', _options)
    }
    t['getUri'] = function(_options) {
      return w.Capacitor.nativePromise('Filesystem', 'getUri', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['WebView'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('WebView', eventName, callback);
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('WebView', 'removeAllListeners', _options)
    }
    t['persistServerBasePath'] = function(_options) {
      return w.Capacitor.nativePromise('WebView', 'persistServerBasePath', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('WebView', 'requestPermissions', _options)
    }
    t['setServerBasePath'] = function(_options) {
      return w.Capacitor.nativePromise('WebView', 'setServerBasePath', _options)
    }
    t['getServerBasePath'] = function(_options) {
      return w.Capacitor.nativePromise('WebView', 'getServerBasePath', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Share'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Share', eventName, callback);
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Share', 'removeAllListeners', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Share', 'requestPermissions', _options)
    }
    t['share'] = function(_options) {
      return w.Capacitor.nativePromise('Share', 'share', _options)
    }
  })(window);

  (function(w) {
    var a = w.Capacitor; var p = a.Plugins;
    var t = p['Browser'] = {};
    t.addListener = function(eventName, callback) {
      return w.Capacitor.addListener('Browser', eventName, callback);
    }
    t['removeAllListeners'] = function(_options) {
      return w.Capacitor.nativeCallback('Browser', 'removeAllListeners', _options)
    }
    t['prefetch'] = function(_options) {
      return w.Capacitor.nativePromise('Browser', 'prefetch', _options)
    }
    t['requestPermissions'] = function(_options) {
      return w.Capacitor.nativePromise('Browser', 'requestPermissions', _options)
    }
    t['close'] = function(_options) {
      return w.Capacitor.nativePromise('Browser', 'close', _options)
    }
    t['open'] = function(_options) {
      return w.Capacitor.nativePromise('Browser', 'open', _options)
    }
  })(window);

  window.WEBVIEW_SERVER_URL = 'https://eh.brickmate.kr/';
</script>
