/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/dist/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/main.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/ckEditor/index.js":
/*!**********************************!*\
  !*** ./src/js/ckEditor/index.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}
window.Steenify = $.extend(Steenify, {
  init__CKEditor: function init__CKEditor() {
    ClassicEditor.create(document.querySelector('[data-CKEditor]')).catch(function (error) {
      console.log(error);
    });
  }
});

/***/ }),

/***/ "./src/js/datepicker/index.js":
/*!************************************!*\
  !*** ./src/js/datepicker/index.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}
window.Steenify = $.extend(Steenify, {
  init__datepicker: function init__datepicker() {
    $('[data-datepicker]').datepicker({
      format: 'yyyy.mm.dd'
    }).datepicker('setDate', new Date());
  }
});

/***/ }),

/***/ "./src/js/event-details/index.js":
/*!***************************************!*\
  !*** ./src/js/event-details/index.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}
window.Steenify = $.extend(Steenify, {
  init__ed: function init__ed() {
    Steenify.init__toggle__comment__ed();
    Steenify.init__toggle__reply__ed();
  },
  init__toggle__comment__ed: function init__toggle__comment__ed() {
    $('.comments__toggle').on('click', function () {
      var self = $(this);
      var content = self.closest('.comments').find('.comments__toggle-content');

      self.toggleClass('opened');
      content.slideToggle();
    });
  },
  init__toggle__reply__ed: function init__toggle__reply__ed() {
    $('.item__reply').on('click', function () {
      var self = $(this);
      var content = self.closest('.item').find('.item__form');

      self.toggleClass('opened');
      content.slideToggle();
    });
  }
});

/***/ }),

/***/ "./src/js/flea-market/index.js":
/*!*************************************!*\
  !*** ./src/js/flea-market/index.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}
window.Steenify = $.extend(Steenify, {
  init__upload__image: function init__upload__image() {
    $('[data-add-image]').on('change', function () {
      var thiz = this,
          files = thiz.files[0];
      if (files) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $(thiz).parent().css('background-image', 'url(' + e.target.result + ')');
          $(thiz).parent().addClass('form-control__invisible');
        };

        reader.readAsDataURL(files);
      }
    });
  }
});

/***/ }),

/***/ "./src/js/home/index.js":
/*!******************************!*\
  !*** ./src/js/home/index.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}
window.Steenify = $.extend(Steenify, {
  init__home: function init__home() {
    Steenify.init_banner__home();
    Steenify.init__toggle__interesting();
    Steenify.init__togglefade__div();
  },
  init_banner__home: function init_banner__home() {
    var banner = $('.banner');
    if (banner.length) {
      banner.slick({
        dots: true,
        nextArrow: '<button type="button" class="slick-next"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M.85 14.169a.483.483 0 0 1-.35.145.495.495 0 0 1-.35-.845l6.155-6.155L.15 1.159a.495.495 0 0 1 .7-.7l6.505 6.505a.495.495 0 0 1 0 .7z"/></g></g></svg></button>',
        prevArrow: '<button type="button" class="slick-prev"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M6.65 14.169a.483.483 0 0 0 .35.145.495.495 0 0 0 .35-.845L1.195 7.314 7.35 1.159a.495.495 0 0 0-.7-.7L.145 6.964a.495.495 0 0 0 0 .7z"/></g></g></svg></button>',
        autoplay: true,
        autoplaySpeed: 3000
      });
    }
  },
  init__toggle__interesting: function init__toggle__interesting() {
    $('.account .account__btn').on('click', function () {
      var self = $(this);
      var id = self.data('id');

      self.toggleClass('opened');

      $('#' + id).stop().slideToggle();
    });
  },
  init__togglefade__div: function init__togglefade__div() {
    $('.togglefade__control').on('click', function (e) {
      e.preventDefault();
      var self = $(this);
      var allInteresting = self.closest('.togglefade').find('.togglefade__content');

      self.toggleClass('opened');

      allInteresting.stop().fadeToggle();
    });
  }
});

/***/ }),

/***/ "./src/js/lecture-evaluation/index.js":
/*!********************************************!*\
  !*** ./src/js/lecture-evaluation/index.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}
window.Steenify = $.extend(Steenify, {
  init__lecture_evaluation: function init__lecture_evaluation() {
    $('.custom-scrollbar').mCustomScrollbar();
  }
});

/***/ }),

/***/ "./src/js/timetable/index.js":
/*!***********************************!*\
  !*** ./src/js/timetable/index.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var jsonDataEx = {
  "time": {
    "from": 9,
    "to": 20
  },
  distance: 50
};

if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}

function convertObjToStyle(obj) {
  return Object.keys(obj).map(function (x) {
    return x + ": " + obj[x];
  }).join(';');
}

window.Steenify = $.extend(Steenify, {
  init__render__timetable: function init__render__timetable() {
    function compileLiteralTemplate(style, id, title, color,group_color) {
        return "<div data-template='#template-info-html' data-group_color='"+group_color+"' data-color='"+ color +"'  class=\"timetable__content-task\" style=\"".concat(convertObjToStyle(style), "\" data-id=").concat(id, ">\n    ").concat(title, "\n  </div>");
      }
    var jsonData = $('[data-timetable]').data('json');
    // const timelineWidth = $('.timetable__content-timeline:first-child').width();
    // const dayWidth = timelineWidth / jsonData.daysOfWeek.length;
    // let taskTemplate = '';
        var config = $('[data-config]').data('config') || { from: 8, to: 20, unit: 1 };
        var showlecture = $('[data-showlecture]').data('showlecture');

    var data = jsonData.reduce(function (a, x) {
      return _extends({}, a, _defineProperty({}, x.day, [].concat(_toConsumableArray(a[x.day] || []), [x])));
    }, {});
    Object.keys(data).forEach(function (key) {
      data[key].forEach(function (item) {
        var position = Math.floor(item.from / config.unit);
        var parentEL = $('[data-day=' + key + ']');
        var dayEL = parentEL.find('[data-start=' + position + ']');
        var color = item.color;
        var group_color = item.group_color;
        var positionOfEl = {
          top: item.from % config.unit * dayEL.outerHeight() + 'px',
          height: (item.to - item.from) / config.unit * dayEL.outerHeight() + 'px',
          'background-color' : color,
        };
        var title = '';
          if (showlecture[1] == 'on') {
            let temp = ''
            if (item.title.length > 5) {
                  temp = '...';
            }
            title = title + item.title.substr(0,5) + temp + '<br/>';
        }
        if (showlecture[2] == 'on') {
            title = title + item.professor_name + '<br/>';
        }
        if (showlecture[3] == 'on') {
            title = title + item.lecture_room;
        }
        // dayEL.append(compileLiteralTemplate(positionOfEl, item.id, title,color,group_color));
      });
    });
  }
});

/***/ }),

/***/ "./src/js/upload-file/index.js":
/*!*************************************!*\
  !*** ./src/js/upload-file/index.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}
window.Steenify = $.extend(Steenify, {
  init__upload__file: function init__upload__file() {
    var template = $('[data-template]').html(),
        container = $('[data-uploaded-content]');

    $('[data-add-link]').on('click', function () {
      var link = $('[data-input-link ]').val();
      if (link.trim()) {
        container.append(template.replace('#fileName', link).replace('#fileType', 'link'));
        $('[data-input-link ]').val('');
      }
    });

    $('[data-add-file]').on('change', function () {
      console.log($(this).val());
      var fileData = $(this)[0].files;
      if (fileData) {
        container.append(template.replace('#fileName', fileData[0].name).replace('#fileType', 'file'));
      }
    });

    $('[data-remove-file]').on('click', function () {
      var link = $('[data-input-link ]').val();
      if (link.trim()) {
        container.append(template.replace('#fileName', link).replace('#fileType', 'link'));
      }
    });
  }
});

/***/ }),

/***/ "./src/main.js":
/*!*********************!*\
  !*** ./src/main.js ***!
  \*********************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(/*! ./scss/index.scss */ "./src/scss/index.scss");

__webpack_require__(/*! ./js/home */ "./src/js/home/index.js");

__webpack_require__(/*! ./js/event-details */ "./src/js/event-details/index.js");

__webpack_require__(/*! ./js/datepicker */ "./src/js/datepicker/index.js");

__webpack_require__(/*! ./js/upload-file */ "./src/js/upload-file/index.js");

__webpack_require__(/*! ./js/flea-market */ "./src/js/flea-market/index.js");

__webpack_require__(/*! ./js/ckEditor */ "./src/js/ckEditor/index.js");

__webpack_require__(/*! ./js/timetable */ "./src/js/timetable/index.js");

__webpack_require__(/*! ./js/lecture-evaluation */ "./src/js/lecture-evaluation/index.js");

if (typeof window.Steenify === 'undefined') {
  window.Steenify = {};
}
// Import js
// Import css


// common js
var clickOrTouch = 'ontouchend' in window ? 'touchend' : 'click';
var lastScroll = 0;

window.Steenify = $.extend(Steenify, {
  init: function init() {
    Steenify.init__header();
    Steenify.init__page();
  },
  init__page: function init__page() {
    var view = $('.ewhaian-page').data('view');
    switch (view) {
      case 'home':
        Steenify.init__home();
        break;
      case 'event-details':
        Steenify.init__ed();
        break;
      case 'advertisement':
        Steenify.init__datepicker();
        break;
    //   case 'advertisement-enrollment':
    //     Steenify.init__upload__file();
    //     Steenify.init__CKEditor();
    //     break;
    //   case 'flea-market':
    //     Steenify.init__datepicker();
    //     Steenify.init__upload__image();
    //     break;
    //   case 'event-comments':
    //     Steenify.init__event_comments();
    //     break;
      case 'timetable':
        Steenify.init__render__timetable();
        break;
      case 'lecture-evaluation':
        Steenify.init__lecture_evaluation();
        break;
      default:
        // console.log(view);
    }
  },
  init__header: function init__header() {
    Steenify.init__datepicker();

    $('.hamburger').on('click', function () {
      var self = $(this);
      var header = $('#header');

      self.toggleClass('opened');
      header.toggleClass('opened');
      header.find('.menu').toggleClass('opened');
    });
  },
  init__page__scroll: function init__page__scroll() {}
});

//***************************************
//      Main program
//***************************************

$(document).ready(function (event) {
  Steenify.init();
});

$(window).on('scroll', function () {
  // Steenify.init__page__scroll();
});

/***/ }),

/***/ "./src/scss/index.scss":
/*!*****************************!*\
  !*** ./src/scss/index.scss ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ })

/******/ });
//# sourceMappingURL=main.js.map
