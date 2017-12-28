/* ========================================================
*
* MVP Ready - Lightweight & Responsive Admin Template
*
* ========================================================
*
* File: mvpready-admin.js
* Theme Version: 2.1.0
* Bootstrap Version: 3.3.5
* Author: Jumpstart Themes
* Website: http://mvpready.com
*
* ======================================================== */

var social_admin = function () {

  "use strict"

  var initNavbarNotifications = function () {
    var notifications = $('.navbar-notification')

    notifications.find ('> .dropdown-toggle').click (function (e) {
      if (social_core.isLayoutCollapsed ()) {
        window.location = $(this).prop ('href')
      }
    })

    notifications.find ('.notification-list').slimScroll ({ height: 225, railVisible: true })
  }

  return {
    init: function () {
      // Layouts
      social_core.navEnhancedInit ()
      social_core.navHoverInit ({ delay: { show: 250, hide: 350 } })      

      initNavbarNotifications ()
      social_core.initLayoutToggles ()
      social_core.initBackToTop ()  

      // Components
      social_helpers.initAccordions ()		
      social_helpers.initFormValidation ()
      social_helpers.initTooltips ()	
      social_helpers.initLightbox ()
      social_helpers.initSelect ()
      social_helpers.initIcheck ()
      social_helpers.initDataTableHelper ()
      social_helpers.initiTimePicker ()
      social_helpers.initDatePicker ()
    }
  }

}()

$(function () {
  social_admin.init ()
})