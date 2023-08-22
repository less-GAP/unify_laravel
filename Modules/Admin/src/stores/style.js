import { defineStore } from "pinia";
import * as styles from "@/styles";
import { darkModeKey, styleKey } from "@/config";
import Format from "@/utils/Format";
export const useStyleStore = defineStore("style", {
  state: () => ({
    theme:{
      headerHeight:'75px',
      sidebarWidth:'255px',
      sidebarCollapsedWidth:'100px',
    },
    format:{
      'created_at':Format.formatDateTime
      ,'updated_at':Format.formatDateTime
    },
    menu:{
      iconSize:28
    },
    antdv:{
      autoInsertSpaceInButton: true,
      componentSize: "large",
      space: {size:"small"},
    },
    /* Styles */
    asideStyle: "",
    asideScrollbarsStyle: "",
    asideBrandStyle: "",
    asideMenuItemStyle: "",
    asideMenuItemActiveStyle: "",
    asideMenuDropdownStyle: "",
    navBarItemLabelStyle: "",
    navBarItemLabelHoverStyle: "",
    navBarItemLabelActiveColorStyle: "",
    overlayStyle: "",

    /* Dark mode */
    darkMode: false,
  }),
  actions: {
    setStyle(payload) {
      if (!styles[payload]) {
        return;
      }

      if (typeof localStorage !== "undefined") {
        localStorage.setItem(styleKey, payload);
      }

      const style = styles[payload];

      for (const key in style) {
        this[`${key}Style`] = style[key];
      }
    },

    setDarkMode(payload = null) {
      this.darkMode = payload !== null ? payload : !this.darkMode;

      if (typeof localStorage !== "undefined") {
        localStorage.setItem(darkModeKey, this.darkMode ? "1" : "0");
      }

      if (typeof document !== "undefined") {
        document.body.classList[this.darkMode ? "add" : "remove"](
          "dark-scrollbars"
        );

        document.documentElement.classList[this.darkMode ? "add" : "remove"](
          "dark-scrollbars-compat"
        );
      }
    },
  },
  persist: {
    enabled: true
  }
});
