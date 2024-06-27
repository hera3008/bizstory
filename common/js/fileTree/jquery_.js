(function () {
    if (typeof E != "undefined") var w = E;
    var E = window.jQuery = function (a, b) {
        return this instanceof E ? this.init(a, b) : new E(a, b);
    };
    if (typeof $ != "undefined") var D = $;
    window.$ = E;
    var u = /^[^<]*(<(.|\s)+>)[^>]*$|^#(\w+)$/;
    var DOMContentLoaded;
    E.fn = E.prototype = {
        init: function (c, a) {
            c = c || document;
            if (typeof c == "string") {
                var m = u.exec(c);
                if (m && (m[1] || !a)) {
                    if (m[1]) c = E.clean([m[1]], a);
                    else {
                        var b = document.getElementById(m[3]);
                        if (b) if (b.id != m[3]) return E().find(c);
                        else {
                            this[0] = b;
                            this.length = 1;
                            return this;
                        } else c = [];
                    }
                } else return new E(a).find(c);
            } else if (E.isFunction(c)) return new E(document)[E.fn.ready ? "ready" : "load"](c);
            return this.setArray(c.constructor == Array && c || (c.jquery || c.length && c != window && !c.nodeType && c[0] != undefined && c[0].nodeType) && E.makeArray(c) || [c]);
        },
        jquery: "1.2.1",
        size: function () {
            return this.length;
        },
        length: 0,
        get: function (a) {
            return a == undefined ? E.makeArray(this) : this[a];
        },
        pushStack: function (a) {
            var b = E(a);
            b.prevObject = this;
            return b;
        },
        setArray: function (a) {
            this.length = 0;
            Array.prototype.push.apply(this, a);
            return this;
        },
        each: function (a, b) {
            return E.each(this, a, b);
        },
        index: function (a) {
            var b = -1;
            this.each(function (i) {
                if (this == a) b = i;
            });
            return b;
        },
        attr: function (f, d, e) {
            var c = f;
            if (f.constructor == String) if (d == undefined) return this.length && E[e || "attr"](this[0], f) || undefined;
            else {
                c = {};
                c[f] = d;
            }
            return this.each(function (a) {
                for (var b in c) E.attr(e ? this.style : this, b, E.prop(this, c[b], e, a, b));
            });
        },
        css: function (b, a) {
            return this.attr(b, a, "curCSS");
        },
        text: function (e) {
            if (typeof e != "object" && e != null) return this.empty().append(document.createTextNode(e));
            var t = "";
            E.each(e || this, function () {
                E.each(this.childNodes, function () {
                    if (this.nodeType != 8) t += this.nodeType != 1 ? this.nodeValue : E.fn.text([this]);
                });
            });
            return t;
        },
        wrapAll: function (b) {
            if (this[0]) E(b, this[0].ownerDocument).clone().insertBefore(this[0]).map(function () {
                var a = this;
                while (a.firstChild) a = a.firstChild;
                return a
            }).append(this);
            return this;
        },
        wrapInner: function (a) {
            return this.each(function () {
                E(this).contents().wrapAll(a);
            });
        },
        wrap: function (a) {
            return this.each(function () {
                E(this).wrapAll(a);
            });
        },
        append: function () {
            return this.domManip(arguments, true, 1, function (a) {
                this.appendChild(a);
            });
        },
        prepend: function () {
            return this.domManip(arguments, true, -1, function (a) {
                this.insertBefore(a, this.firstChild);
            });
        },
        before: function () {
            return this.domManip(arguments, false, 1, function (a) {
                this.parentNode.insertBefore(a, this);
            });
        },
        after: function () {
            return this.domManip(arguments, false, -1, function (a) {
                this.parentNode.insertBefore(a, this.nextSibling);
            });
        },
        end: function () {
            return this.prevObject || E([]);
        },
        find: function (t) {
            var b = E.map(this, function (a) {
                return E.find(t, a);
            });
            return this.pushStack(/[^+>] [^+>]/.test(t) || t.indexOf("..") > -1 ? E.unique(b) : b);
        },
        clone: function (e) {
            var f = this.map(function () {
                return this.outerHTML ? E(this.outerHTML)[0] : this.cloneNode(true);
            });
            var d = f.find("*").andSelf().each(function () {
                if (this[F] != undefined) this[F] = null;
            });
            if (e === true) this.find("*").andSelf().each(function (i) {
                var c = E.data(this, "events");
                for (var a in c) for (var b in c[a]) E.event.add(d[i], a, c[a][b], c[a][b].data);
            });
            return f;
        },
        filter: function (t) {
            return this.pushStack(E.isFunction(t) && E.grep(this, function (b, a) {
                return t.apply(b, [a]);
            }) || E.multiFilter(t, this));
        },
        not: function (t) {
            return this.pushStack(t.constructor == String && E.multiFilter(t, this, true) || E.grep(this, function (a) {
                return (t.constructor == Array || t.jquery) ? E.inArray(a, t) < 0 : a != t;
            }));
        },
        add: function (t) {
            return this.pushStack(E.merge(this.get(), t.constructor == String ? E(t).get() : t.length != undefined && (!t.nodeName || E.nodeName(t, "form")) ? t : [t]));
        },
        is: function (a) {
            return a ? E.multiFilter(a, this).length > 0 : false;
        },
        hasClass: function (a) {
            return this.is("." + a);
        },
        val: function (b) {
            if (b == undefined) {
                if (this.length) {
                    var c = this[0];
                    if (E.nodeName(c, "select")) {
                        var e = c.selectedIndex,
                            a = [],
                            options = c.options,
                            one = c.type == "select-one";
                        if (e < 0) return null;
                        for (var i = one ? e : 0, max = one ? e + 1 : options.length; i < max; i++) {
                            var d = options[i];
                            if (d.selected) {
                                var b = E.browser.msie && !d.attributes["value"].specified ? d.text : d.value;
                                if (one) return b;
                                a.push(b)
                            }
                        }
                        return a
                    } else return this[0].value.replace(/\r/g, "");
                }
            } else return this.each(function () {
                if (b.constructor == Array && /radio|checkbox/.test(this.type)) this.checked = (E.inArray(this.value, b) >= 0 || E.inArray(this.name, b) >= 0);
                else if (E.nodeName(this, "select")) {
                    var a = b.constructor == Array ? b : [b];
                    E("option", this).each(function () {
                        this.selected = (E.inArray(this.value, a) >= 0 || E.inArray(this.text, a) >= 0);
                    });
                    if (!a.length) this.selectedIndex = -1;
                } else this.value = b;
            });
        },
        html: function (a) {
            return a == undefined ? (this.length ? this[0].innerHTML : null) : this.empty().append(a);
        },
        replaceWith: function (a) {
            return this.after(a).remove();
        },
        eq: function (i) {
            return this.slice(i, i + 1);
        },
        slice: function () {
            return this.pushStack(Array.prototype.slice.apply(this, arguments));
        },
        map: function (b) {
            return this.pushStack(E.map(this, function (a, i) {
                return b.call(a, i, a);
            }));
        },
        andSelf: function () {
            return this.add(this.prevObject);
        },
        domManip: function (f, d, g, e) {
            var c = this.length > 1,
                a;
            return this.each(function () {
                if (!a) {
                    a = E.clean(f, this.ownerDocument);
                    if (g < 0) a.reverse();
                }
                var b = this;
                if (d && E.nodeName(this, "table") && E.nodeName(a[0], "tr")) b = this.getElementsByTagName("tbody")[0] || this.appendChild(document.createElement("tbody"));
                E.each(a, function () {
                    var a = c ? this.cloneNode(true) : this;
                    if (!evalScript(0, a)) e.call(b, a);
                });
            });
        }
    };

    function evalScript(i, b) {
        var a = E.nodeName(b, "script");
        if (a) {
            if (b.src) E.ajax({
                url: b.src,
                async: false,
                dataType: "script"
            });
            else E.globalEval(b.text || b.textContent || b.innerHTML || "");
            if (b.parentNode) b.parentNode.removeChild(b);
        } else if (b.nodeType == 1) E("script", b).each(evalScript);
        return a;
    }
    E.extend = E.fn.extend = function () {
        var c = arguments[0] || {}, a = 1,
            al = arguments.length,
            deep = false;
        if (c.constructor == Boolean) {
            deep = c;
            c = arguments[1] || {};
        }
        if (al == 1) {
            c = this;
            a = 0;
        }
        var b;
        for (; a < al; a++) if ((b = arguments[a]) != null) for (var i in b) {
            if (c == b[i]) continue;
            if (deep && typeof b[i] == 'object' && c[i]) E.extend(c[i], b[i]);
            else if (b[i] != undefined) c[i] = b[i];
        }
        return c;
    };
    var F = "jQuery" + (new Date()).getTime(),
        uuid = 0,
        win = {};
    E.extend({
        noConflict: function (a) {
            window.$ = D;
            if (a) window.jQuery = w;
            return E;
        },
        isFunction: function (a) {
            return !!a && typeof a != "string" && !a.nodeName && a.constructor != Array && /function/i.test(a + "");
        },
        isXMLDoc: function (a) {
            return a.documentElement && !a.body || a.tagName && a.ownerDocument && !a.ownerDocument.body;
        },
        globalEval: function (a) {
            a = E.trim(a);
            if (a) {
                if (window.execScript) window.execScript(a);
                else if (E.browser.safari) window.setTimeout(a, 0);
                else eval.call(window, a);
            }
        },
        nodeName: function (b, a) {
            return b.nodeName && b.nodeName.toUpperCase() == a.toUpperCase();
        },
        cache: {},
        data: function (c, d, b) {
            c = c == window ? win : c;
            var a = c[F];
            if (!a) a = c[F] = ++uuid;
            if (d && !E.cache[a]) E.cache[a] = {};
            if (b != undefined) E.cache[a][d] = b;
            return d ? E.cache[a][d] : a;
        },
        removeData: function (c, b) {
            c = c == window ? win : c;
            var a = c[F];
            if (b) {
                if (E.cache[a]) {
                    delete E.cache[a][b];
                    b = "";
                    for (b in E.cache[a]) break;
                    if (!b) E.removeData(c);
                }
            } else {
                try {
                    delete c[F];
                } catch (e) {
                    if (c.removeAttribute) c.removeAttribute(F);
                }
                delete E.cache[a];
            }
        },
        each: function (a, b, c) {
            if (c) {
                if (a.length == undefined) for (var i in a) b.apply(a[i], c);
                else for (var i = 0, ol = a.length; i < ol; i++) if (b.apply(a[i], c) === false) break;
            } else {
                if (a.length == undefined) for (var i in a) b.call(a[i], i, a[i]);
                else for (var i = 0, ol = a.length, val = a[0]; i < ol && b.call(val, i, val) !== false; val = a[++i]) {}
            }
            return a;
        },
        prop: function (c, b, d, e, a) {
            if (E.isFunction(b)) b = b.call(c, [e]);
            var f = /z-?index|font-?weight|opacity|zoom|line-?height/i;
            return b && b.constructor == Number && d == "curCSS" && !f.test(a) ? b + "px" : b;
        },
        className: {
            add: function (b, c) {
                E.each((c || "").split(/\s+/), function (i, a) {
                    if (!E.className.has(b.className, a)) b.className += (b.className ? " " : "") + a;
                });
            },
            remove: function (b, c) {
                b.className = c != undefined ? E.grep(b.className.split(/\s+/), function (a) {
                    return !E.className.has(c, a);
                }).join(" ") : "";
            },
            has: function (t, c) {
                return E.inArray(c, (t.className || t).toString().split(/\s+/)) > -1;
            }
        },
        swap: function (e, o, f) {
            for (var i in o) {
                e.style["old" + i] = e.style[i];
                e.style[i] = o[i];
            }
            f.apply(e, []);
            for (var i in o) e.style[i] = e.style["old" + i];
        },
        css: function (e, p) {
            if (p == "height" || p == "width") {
                var b = {}, oHeight, oWidth, d = ["Top", "Bottom", "Right", "Left"];
                E.each(d, function () {
                    b["padding" + this] = 0;
                    b["border" + this + "Width"] = 0;
                });
                E.swap(e, b, function () {
                    if (E(e).is(':visible')) {
                        oHeight = e.offsetHeight;
                        oWidth = e.offsetWidth;
                    } else {
                        e = E(e.cloneNode(true)).find(":radio").removeAttr("checked").end().css({
                            visibility: "hidden",
                            position: "absolute",
                            display: "block",
                            right: "0",
                            left: "0"
                        }).appendTo(e.parentNode)[0];
                        var a = E.css(e.parentNode, "position") || "static";
                        if (a == "static") e.parentNode.style.position = "relative";
                        oHeight = e.clientHeight;
                        oWidth = e.clientWidth;
                        if (a == "static") e.parentNode.style.position = "static";
                        e.parentNode.removeChild(e);
                    }
                });
                return p == "height" ? oHeight : oWidth;
            }
            return E.curCSS(e, p);
        },
        curCSS: function (h, j, i) {
            var g, stack = [],
                swap = [];

            function color(a) {
                if (!E.browser.safari) return false;
                var b = document.defaultView.getComputedStyle(a, null);
                return !b || b.getPropertyValue("color") == "";
            }
            if (j == "opacity" && E.browser.msie) {
                g = E.attr(h.style, "opacity");
                return g == "" ? "1" : g;
            }
            if (j.match(/float/i)) j = y;
            if (!i && h.style[j]) g = h.style[j];
            else if (document.defaultView && document.defaultView.getComputedStyle) {
                if (j.match(/float/i)) j = "float";
                j = j.replace(/([A-Z])/g, "-$1").toLowerCase();
                var d = document.defaultView.getComputedStyle(h, null);
                if (d && !color(h)) g = d.getPropertyValue(j);
                else {
                    for (var a = h; a && color(a); a = a.parentNode) stack.unshift(a);
                    for (a = 0; a < stack.length; a++) if (color(stack[a])) {
                        swap[a] = stack[a].style.display;
                        stack[a].style.display = "block";
                    }
                    g = j == "display" && swap[stack.length - 1] != null ? "none" : document.defaultView.getComputedStyle(h, null).getPropertyValue(j) || "";
                    for (a = 0; a < swap.length; a++) if (swap[a] != null) stack[a].style.display = swap[a];
                }
                if (j == "opacity" && g == "") g = "1";
            } else if (h.currentStyle) {
                var f = j.replace(/\-(\w)/g, function (m, c) {
                    return c.toUpperCase();
                });
                g = h.currentStyle[j] || h.currentStyle[f];
                if (!/^\d+(px)?$/i.test(g) && /^\d/.test(g)) {
                    var k = h.style.left;
                    var e = h.runtimeStyle.left;
                    h.runtimeStyle.left = h.currentStyle.left;
                    h.style.left = g || 0;
                    g = h.style.pixelLeft + "px";
                    h.style.left = k;
                    h.runtimeStyle.left = e;
                }
            }
            return g;
        },
        clean: function (a, e) {
            var r = [];
            e = e || document;
            E.each(a, function (i, d) {
                if (!d) return;
                if (d.constructor == Number) d = d.toString();
                if (typeof d == "string") {
                    d = d.replace(/(<(\w+)[^>]*?)\/>/g, function (m, a, b) {
                        return b.match(/^(abbr|br|col|img|input|link|meta|param|hr|area)$/i) ? m : a + "></" + b + ">";
                    });
                    var s = E.trim(d).toLowerCase(),
                        div = e.createElement("div"),
                        tb = [];
                    var c = !s.indexOf("<opt") && [1, "<select>", "</select>"] || !s.indexOf("<leg") && [1, "<fieldset>", "</fieldset>"] || s.match(/^<(thead|tbody|tfoot|colg|cap)/) && [1, "<table>", "</table>"] || !s.indexOf("<tr") && [2, "<table><tbody>", "</tbody></table>"] || (!s.indexOf("<td") || !s.indexOf("<th")) && [3, "<table><tbody><tr>", "</tr></tbody></table>"] || !s.indexOf("<col") && [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"] || E.browser.msie && [1, "div<div>", "</div>"] || [0, "", ""];
                    div.innerHTML = c[1] + d + c[2];
                    while (c[0]--) div = div.lastChild;
                    if (E.browser.msie) {
                        if (!s.indexOf("<table") && s.indexOf("<tbody") < 0) tb = div.firstChild && div.firstChild.childNodes;
                        else if (c[1] == "<table>" && s.indexOf("<tbody") < 0) tb = div.childNodes;
                        for (var n = tb.length - 1; n >= 0; --n) if (E.nodeName(tb[n], "tbody") && !tb[n].childNodes.length) tb[n].parentNode.removeChild(tb[n]);
                        if (/^\s/.test(d)) div.insertBefore(e.createTextNode(d.match(/^\s*/)[0]), div.firstChild);
                    }
                    d = E.makeArray(div.childNodes);
                }
                if (0 === d.length && (!E.nodeName(d, "form") && !E.nodeName(d, "select"))) return;
                if (d[0] == undefined || E.nodeName(d, "form") || d.options) r.push(d);
                else r = E.merge(r, d);
            });
            return r;
        },
        attr: function (c, d, a) {
            var e = E.isXMLDoc(c) ? {} : E.props;
            if (d == "selected" && E.browser.safari) c.parentNode.selectedIndex;
            if (e[d]) {
                if (a != undefined) c[e[d]] = a;
                return c[e[d]];
            } else if (E.browser.msie && d == "style") return E.attr(c.style, "cssText", a);
            else if (a == undefined && E.browser.msie && E.nodeName(c, "form") && (d == "action" || d == "method")) return c.getAttributeNode(d).nodeValue;
            else if (c.tagName) {
                if (a != undefined) {
                    if (d == "type" && E.nodeName(c, "input") && c.parentNode) throw "type property can't be changed";
                    c.setAttribute(d, a);
                }
                if (E.browser.msie && /href|src/.test(d) && !E.isXMLDoc(c)) return c.getAttribute(d, 2);
                return c.getAttribute(d);
            } else {
                if (d == "opacity" && E.browser.msie) {
                    if (a != undefined) {
                        c.zoom = 1;
                        c.filter = (c.filter || "").replace(/alpha\([^)]*\)/, "") + (parseFloat(a).toString() == "NaN" ? "" : "alpha(opacity=" + a * 100 + ")");
                    }
                    return c.filter ? (parseFloat(c.filter.match(/opacity=([^)]*)/)[1]) / 100).toString() : "";
                }
                d = d.replace(/-([a-z])/ig, function (z, b) {
                    return b.toUpperCase();
                });
                if (a != undefined) c[d] = a;
                return c[d];
            }
        },
        trim: function (t) {
            return (t || "").replace(/^\s+|\s+$/g, "");
        },
        makeArray: function (a) {
            var r = [];
            if (typeof a != "array") for (var i = 0, al = a.length; i < al; i++) r.push(a[i]);
            else r = a.slice(0);
            return r;
        },
        inArray: function (b, a) {
            for (var i = 0, al = a.length; i < al; i++) if (a[i] == b) return i;
            return -1;
        },
        merge: function (a, b) {
            if (E.browser.msie) {
                for (var i = 0; b[i]; i++) if (b[i].nodeType != 8) a.push(b[i]);
            } else for (var i = 0; b[i]; i++) a.push(b[i]);
            return a;
        },
        unique: function (b) {
            var r = [],
                done = {};
            try {
                for (var i = 0, fl = b.length; i < fl; i++) {
                    var a = E.data(b[i]);
                    if (!done[a]) {
                        done[a] = true;
                        r.push(b[i]);
                    }
                }
            } catch (e) {
                r = b;
            }
            return r;
        },
        grep: function (b, a, c) {
            if (typeof a == "string") a = eval("false||function(a,i){return " + a + "}");
            var d = [];
            for (var i = 0, el = b.length; i < el; i++) if (!c && a(b[i], i) || c && !a(b[i], i)) d.push(b[i]);
            return d;
        },
        map: function (c, b) {
            if (typeof b == "string") b = eval("false||function(a){return " + b + "}");
            var d = [];
            for (var i = 0, el = c.length; i < el; i++) {
                var a = b(c[i], i);
                if (a !== null && a != undefined) {
                    if (a.constructor != Array) a = [a];
                    d = d.concat(a);
                }
            }
            return d;
        }
    });
    var v = navigator.userAgent.toLowerCase();
    E.browser = {
        version: (v.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
        safari: /webkit/.test(v),
        opera: /opera/.test(v),
        msie: /msie/.test(v) && !/opera/.test(v),
        mozilla: /mozilla/.test(v) && !/(compatible|webkit)/.test(v)
    };
    var y = E.browser.msie ? "styleFloat" : "cssFloat";
    E.extend({
        boxModel: !E.browser.msie || document.compatMode == "CSS1Compat",
        styleFloat: E.browser.msie ? "styleFloat" : "cssFloat",
        props: {
            "for": "htmlFor",
            "class": "className",
            "float": y,
            cssFloat: y,
            styleFloat: y,
            innerHTML: "innerHTML",
            className: "className",
            value: "value",
            disabled: "disabled",
            checked: "checked",
            readonly: "readOnly",
            selected: "selected",
            maxlength: "maxLength"
        }
    });
    E.each({
        parent: "a.parentNode",
        parents: "jQuery.dir(a,'parentNode')",
        next: "jQuery.nth(a,2,'nextSibling')",
        prev: "jQuery.nth(a,2,'previousSibling')",
        nextAll: "jQuery.dir(a,'nextSibling')",
        prevAll: "jQuery.dir(a,'previousSibling')",
        siblings: "jQuery.sibling(a.parentNode.firstChild,a)",
        children: "jQuery.sibling(a.firstChild)",
        contents: "jQuery.nodeName(a,'iframe')?a.contentDocument||a.contentWindow.document:jQuery.makeArray(a.childNodes)"
    }, function (i, n) {
        E.fn[i] = function (a) {
            var b = E.map(this, n);
            if (a && typeof a == "string") b = E.multiFilter(a, b);
            return this.pushStack(E.unique(b));
        };
    });
    E.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (i, n) {
        E.fn[i] = function () {
            var a = arguments;
            return this.each(function () {
                for (var j = 0, al = a.length; j < al; j++) E(a[j])[n](this);
            });
        };
    });
    E.each({
        removeAttr: function (a) {
            E.attr(this, a, "");
            this.removeAttribute(a);
        },
        addClass: function (c) {
            E.className.add(this, c);
        },
        removeClass: function (c) {
            E.className.remove(this, c);
        },
        toggleClass: function (c) {
            E.className[E.className.has(this, c) ? "remove" : "add"](this, c);
        },
        remove: function (a) {
            if (!a || E.filter(a, [this]).r.length) {
                E.removeData(this);
                this.parentNode.removeChild(this);
            }
        },
        empty: function () {
            E("*", this).each(function () {
                E.removeData(this);
            });
            while (this.firstChild) this.removeChild(this.firstChild);
        }
    }, function (i, n) {
        E.fn[i] = function () {
            return this.each(n, arguments);
        };
    });
    E.each(["Height", "Width"], function (i, a) {
        var n = a.toLowerCase();
        E.fn[n] = function (h) {
            return this[0] == window ? E.browser.safari && self["inner" + a] || E.boxModel && Math.max(document.documentElement["client" + a], document.body["client" + a]) || document.body["client" + a] : this[0] == document ? Math.max(document.body["scroll" + a], document.body["offset" + a]) : h == undefined ? (this.length ? E.css(this[0], n) : null) : this.css(n, h.constructor == String ? h : h + "px");
        };
    });
    var C = E.browser.safari && parseInt(E.browser.version) < 417 ? "(?:[\\w*_-]|\\\\.)" : "(?:[\\w\u0128-\uFFFF*_-]|\\\\.)",
        quickChild = new RegExp("^>\\s*(" + C + "+)"),
        quickID = new RegExp("^(" + C + "+)(#)(" + C + "+)"),
        quickClass = new RegExp("^([#.]?)(" + C + "*)");
    E.extend({
        expr: {
            "": "m[2]=='*'||jQuery.nodeName(a,m[2])",
            "#": "a.getAttribute('id')==m[2]",
            ":": {
                lt: "i<m[3]-0",
                gt: "i>m[3]-0",
                nth: "m[3]-0==i",
                eq: "m[3]-0==i",
                first: "i==0",
                last: "i==r.length-1",
                even: "i%2==0",
                odd: "i%2",
                "first-child": "a.parentNode.getElementsByTagName('*')[0]==a",
                "last-child": "jQuery.nth(a.parentNode.lastChild,1,'previousSibling')==a",
                "only-child": "!jQuery.nth(a.parentNode.lastChild,2,'previousSibling')",
                parent: "a.firstChild",
                empty: "!a.firstChild",
                contains: "(a.textContent||a.innerText||jQuery(a).text()||'').indexOf(m[3])>=0",
                visible: '"hidden"!=a.type&&jQuery.css(a,"display")!="none"&&jQuery.css(a,"visibility")!="hidden"',
                hidden: '"hidden"==a.type||jQuery.css(a,"display")=="none"||jQuery.css(a,"visibility")=="hidden"',
                enabled: "!a.disabled",
                disabled: "a.disabled",
                checked: "a.checked",
                selected: "a.selected||jQuery.attr(a,'selected')",
                text: "'text'==a.type",
                radio: "'radio'==a.type",
                checkbox: "'checkbox'==a.type",
                file: "'file'==a.type",
                password: "'password'==a.type",
                submit: "'submit'==a.type",
                image: "'image'==a.type",
                reset: "'reset'==a.type",
                button: '"button"==a.type||jQuery.nodeName(a,"button")',
                input: "/input|select|textarea|button/i.test(a.nodeName)",
                has: "jQuery.find(m[3],a).length",
                header: "/h\\d/i.test(a.nodeName)",
                animated: "jQuery.grep(jQuery.timers,function(fn){return a==fn.elem;}).length"
            }
        },
        parse: [/^(\[) *@?([\w-]+) *([!*$^~=]*) *('?"?)(.*?)\4 *\]/, /^(:)([\w-]+)\("?'?(.*?(\(.*?\))?[^(]*?)"?'?\)/, new RegExp("^([:.#]*)(" + C + "+)")],
        multiFilter: function (a, c, b) {
            var d, cur = [];
            while (a && a != d) {
                d = a;
                var f = E.filter(a, c, b);
                a = f.t.replace(/^\s*,\s*/, "");
                cur = b ? c = f.r : E.merge(cur, f.r);
            }
            return cur;
        },
        find: function (t, o) {
            if (typeof t != "string") return [t];
            if (o && !o.nodeType) o = null;
            o = o || document;
            var d = [o],
                done = [],
                last;
            while (t && last != t) {
                var r = [];
                last = t;
                t = E.trim(t);
                var l = false;
                var g = quickChild;
                var m = g.exec(t);
                if (m) {
                    var p = m[1].toUpperCase();
                    for (var i = 0; d[i]; i++) for (var c = d[i].firstChild; c; c = c.nextSibling) if (c.nodeType == 1 && (p == "*" || c.nodeName.toUpperCase() == p.toUpperCase())) r.push(c);
                    d = r;
                    t = t.replace(g, "");
                    if (t.indexOf(" ") == 0) continue;
                    l = true;
                } else {
                    g = /^([>+~])\s*(\w*)/i;
                    if ((m = g.exec(t)) != null) {
                        r = [];
                        var p = m[2],
                            merge = {};
                        m = m[1];
                        for (var j = 0, rl = d.length; j < rl; j++) {
                            var n = m == "~" || m == "+" ? d[j].nextSibling : d[j].firstChild;
                            for (; n; n = n.nextSibling) if (n.nodeType == 1) {
                                var h = E.data(n);
                                if (m == "~" && merge[h]) break;
                                if (!p || n.nodeName.toUpperCase() == p.toUpperCase()) {
                                    if (m == "~") merge[h] = true;
                                    r.push(n);
                                }
                                if (m == "+") break;
                            }
                        }
                        d = r;
                        t = E.trim(t.replace(g, ""));
                        l = true;
                    }
                }
                if (t && !l) {
                    if (!t.indexOf(",")) {
                        if (o == d[0]) d.shift();
                        done = E.merge(done, d);
                        r = d = [o];
                        t = " " + t.substr(1, t.length);
                    } else {
                        var k = quickID;
                        var m = k.exec(t);
                        if (m) {
                            m = [0, m[2], m[3], m[1]];
                        } else {
                            k = quickClass;
                            m = k.exec(t);
                        }
                        m[2] = m[2].replace(/\\/g, "");
                        var f = d[d.length - 1];
                        if (m[1] == "#" && f && f.getElementById && !E.isXMLDoc(f)) {
                            var q = f.getElementById(m[2]);
                            if ((E.browser.msie || E.browser.opera) && q && typeof q.id == "string" && q.id != m[2]) q = E('[@id="' + m[2] + '"]', f)[0];
                            d = r = q && (!m[3] || E.nodeName(q, m[3])) ? [q] : [];
                        } else {
                            for (var i = 0; d[i]; i++) {
                                var a = m[1] == "#" && m[3] ? m[3] : m[1] != "" || m[0] == "" ? "*" : m[2];
                                if (a == "*" && d[i].nodeName.toLowerCase() == "object") a = "param";
                                r = E.merge(r, d[i].getElementsByTagName(a));
                            }
                            if (m[1] == ".") r = E.classFilter(r, m[2]);
                            if (m[1] == "#") {
                                var e = [];
                                for (var i = 0; r[i]; i++) if (r[i].getAttribute("id") == m[2]) {
                                    e = [r[i]];
                                    break;
                                }
                                r = e;
                            }
                            d = r;
                        }
                        t = t.replace(k, "");
                    }
                }
                if (t) {
                    var b = E.filter(t, r);
                    d = r = b.r;
                    t = E.trim(b.t);
                }
            }
            if (t) d = [];
            if (d && o == d[0]) d.shift();
            done = E.merge(done, d);
            return done;
        },
        classFilter: function (r, m, a) {
            m = " " + m + " ";
            var c = [];
            for (var i = 0; r[i]; i++) {
                var b = (" " + r[i].className + " ").indexOf(m) >= 0;
                if (!a && b || a && !b) c.push(r[i]);
            }
            return c;
        },
        filter: function (t, r, h) {
            var d;
            while (t && t != d) {
                d = t;
                var p = E.parse,
                    m;
                for (var i = 0; p[i]; i++) {
                    m = p[i].exec(t);
                    if (m) {
                        t = t.substring(m[0].length);
                        m[2] = m[2].replace(/\\/g, "");
                        break;
                    }
                }
                if (!m) break;
                if (m[1] == ":" && m[2] == "not") r = E.filter(m[3], r, true).r;
                else if (m[1] == ".") r = E.classFilter(r, m[2], h);
                else if (m[1] == "[") {
                    var g = [],
                        type = m[3];
                    for (var i = 0, rl = r.length; i < rl; i++) {
                        var a = r[i],
                            z = a[E.props[m[2]] || m[2]];
                        if (z == null || /href|src|selected/.test(m[2])) z = E.attr(a, m[2]) || '';
                        if ((type == "" && !! z || type == "=" && z == m[5] || type == "!=" && z != m[5] || type == "^=" && z && !z.indexOf(m[5]) || type == "$=" && z.substr(z.length - m[5].length) == m[5] || (type == "*=" || type == "~=") && z.indexOf(m[5]) >= 0) ^ h) g.push(a);
                    };
                    r = g;
                } else if (m[1] == ":" && m[2] == "nth-child") {
                    var e = {}, g = [],
                        test = /(\d*)n\+?(\d*)/.exec(m[3] == "even" && "2n" || m[3] == "odd" && "2n+1" || !/\D/.test(m[3]) && "n+" + m[3] || m[3]),
                        first = (test[1] || 1) - 0,
                        d = test[2] - 0;
                    for (var i = 0, rl = r.length; i < rl; i++) {
                        var j = r[i],
                            parentNode = j.parentNode,
                            id = E.data(parentNode);
                        if (!e[id]) {
                            var c = 1;
                            for (var n = parentNode.firstChild; n; n = n.nextSibling) if (n.nodeType == 1) n.nodeIndex = c++;
                            e[id] = true;
                        }
                        var b = false;
                        if (first == 1) {
                            if (d == 0 || j.nodeIndex == d) b = true;
                        } else if ((j.nodeIndex + d) % first == 0) b = true;
                        if (b ^ h) g.push(j);
                    }
                    r = g;
                } else {
                    var f = E.expr[m[1]];
                    if (typeof f != "string") f = E.expr[m[1]][m[2]];
                    f = eval("false||function(a,i){return " + f + "}");
                    r = E.grep(r, f, h);
                }
            }
            return {
                r: r,
                t: t
            };
        },
        dir: function (b, c) {
            var d = [];
            var a = b[c];
            while (a && a != document) {
                if (a.nodeType == 1) d.push(a);
                a = a[c];
            }
            return d;
        },
        nth: function (a, e, c, b) {
            e = e || 1;
            var d = 0;
            for (; a; a = a[c]) if (a.nodeType == 1 && ++d == e) break;
            return a;
        },
        sibling: function (n, a) {
            var r = [];
            for (; n; n = n.nextSibling) {
                if (n.nodeType == 1 && (!a || n != a)) r.push(n);
            }
            return r;
        }
    });
    E.event = {
        add: function (g, e, c, h) {
            if (E.browser.msie && g.setInterval != undefined) g = window;
            if (!c.guid) c.guid = this.guid++;
            if (h != undefined) {
                var d = c;
                c = function () {
                    return d.apply(this, arguments);
                };
                c.data = h;
                c.guid = d.guid;
            }
            var i = e.split(".");
            e = i[0];
            c.type = i[1];
            var b = E.data(g, "events") || E.data(g, "events", {});
            var f = E.data(g, "handle", function () {
                var a;
                if (typeof E == "undefined" || E.event.triggered) return a;
                a = E.event.handle.apply(g, arguments);
                return a;
            });
            var j = b[e];
            if (!j) {
                j = b[e] = {};
                if (g.addEventListener) g.addEventListener(e, f, false);
                else g.attachEvent("on" + e, f);
            }
            j[c.guid] = c;
            this.global[e] = true;
        },
        guid: 1,
        global: {},
        remove: function (d, c, b) {
            var e = E.data(d, "events"),
                ret, index;
            if (typeof c == "string") {
                var a = c.split(".");
                c = a[0];
            }
            if (e) {
                if (c && c.type) {
                    b = c.handler;
                    c = c.type;
                }
                if (!c) {
                    for (c in e) this.remove(d, c);
                } else if (e[c]) {
                    if (b) delete e[c][b.guid];
                    else for (b in e[c]) if (!a[1] || e[c][b].type == a[1]) delete e[c][b];
                    for (ret in e[c]) break;
                    if (!ret) {
                        if (d.removeEventListener) d.removeEventListener(c, E.data(d, "handle"), false);
                        else d.detachEvent("on" + c, E.data(d, "handle"));
                        ret = null;
                        delete e[c];
                    }
                }
                for (ret in e) break;
                if (!ret) {
                    E.removeData(d, "events");
                    E.removeData(d, "handle");
                }
            }
        },
        trigger: function (d, b, e, c, f) {
            b = E.makeArray(b || []);
            if (!e) {
                if (this.global[d]) E("*").add([window, document]).trigger(d, b);
            } else {
                var a, ret, fn = E.isFunction(e[d] || null),
                    evt = !b[0] || !b[0].preventDefault;
                if (evt) b.unshift(this.fix({
                    type: d,
                    target: e
                }));
                b[0].type = d;
                if (E.isFunction(E.data(e, "handle"))) a = E.data(e, "handle").apply(e, b);
                if (!fn && e["on" + d] && e["on" + d].apply(e, b) === false) a = false;
                if (evt) b.shift();
                if (f && f.apply(e, b) === false) a = false;
                if (fn && c !== false && a !== false && !(E.nodeName(e, 'a') && d == "click")) {
                    this.triggered = true;
                    e[d]();
                }
                this.triggered = false;
            }
            return a;
        },
        handle: function (d) {
            var a;
            d = E.event.fix(d || window.event || {});
            var b = d.type.split(".");
            d.type = b[0];
            var c = E.data(this, "events") && E.data(this, "events")[d.type],
                args = Array.prototype.slice.call(arguments, 1);
            args.unshift(d);
            for (var j in c) {
                args[0].handler = c[j];
                args[0].data = c[j].data;
                if (!b[1] || c[j].type == b[1]) {
                    var e = c[j].apply(this, args);
                    if (a !== false) a = e;
                    if (e === false) {
                        d.preventDefault();
                        d.stopPropagation();
                    }
                }
            }
            if (E.browser.msie) d.target = d.preventDefault = d.stopPropagation = d.handler = d.data = null;
            return a;
        },
        fix: function (c) {
            var a = c;
            c = E.extend({}, a);
            c.preventDefault = function () {
                if (a.preventDefault) a.preventDefault();
                a.returnValue = false;
            };
            c.stopPropagation = function () {
                if (a.stopPropagation) a.stopPropagation();
                a.cancelBubble = true;
            };
            if (!c.target && c.srcElement) c.target = c.srcElement;
            if (E.browser.safari && c.target.nodeType == 3) c.target = a.target.parentNode;
            if (!c.relatedTarget && c.fromElement) c.relatedTarget = c.fromElement == c.target ? c.toElement : c.fromElement;
            if (c.pageX == null && c.clientX != null) {
                var e = document.documentElement,
                    b = document.body;
                c.pageX = c.clientX + (e && e.scrollLeft || b.scrollLeft || 0);
                c.pageY = c.clientY + (e && e.scrollTop || b.scrollTop || 0);
            }
            if (!c.which && (c.charCode || c.keyCode)) c.which = c.charCode || c.keyCode;
            if (!c.metaKey && c.ctrlKey) c.metaKey = c.ctrlKey;
            if (!c.which && c.button) c.which = (c.button & 1 ? 1 : (c.button & 2 ? 3 : (c.button & 4 ? 2 : 0)));
            return c;
        }
    };
    E.fn.extend({
        bind: function (c, a, b) {
            return c == "unload" ? this.one(c, a, b) : this.each(function () {
                E.event.add(this, c, b || a, b && a);
            });
        },
        one: function (d, b, c) {
            return this.each(function () {
                E.event.add(this, d, function (a) {
                    E(this).unbind(a);
                    return (c || b).apply(this, arguments);
                }, c && b);
            });
        },
        unbind: function (a, b) {
            return this.each(function () {
                E.event.remove(this, a, b);
            });
        },
        trigger: function (c, a, b) {
            return this.each(function () {
                E.event.trigger(c, a, this, true, b);
            });
        },
        triggerHandler: function (c, a, b) {
            if (this[0]) return E.event.trigger(c, a, this[0], false, b);
        },
        toggle: function () {
            var a = arguments;
            return this.click(function (e) {
                this.lastToggle = 0 == this.lastToggle ? 1 : 0;
                e.preventDefault();
                return a[this.lastToggle].apply(this, [e]) || false;
            });
        },
        hover: function (f, g) {
            function handleHover(e) {
                var p = e.relatedTarget;
                while (p && p != this) try {
                    p = p.parentNode;
                } catch (e) {
                    p = this;
                };
                if (p == this) return false;
                return (e.type == "mouseover" ? f : g).apply(this, [e]);
            }
            return this.mouseover(handleHover).mouseout(handleHover);
        },
        ready: function (f) {
            bindReady();
            if (E.isReady) f.apply(document, [E]);
            else E.readyList.push(function () {
                return f.apply(this, [E]);
            });
            return this;
        }
    });
    E.extend({
        isReady: false,
        readyList: [],
        ready: function () {
            if (!E.isReady) {
                E.isReady = true;
                if (E.readyList) {
                    E.each(E.readyList, function () {
                        this.apply(document);
                    });
                    E.readyList = null;
                }
                if (E.browser.mozilla || E.browser.opera) document.removeEventListener("DOMContentLoaded", E.ready, false);
                if (!window.frames.length) E(window).load(function () {
                    E("#__ie_init").remove();
                });
            }
        }
    });
    E.each(("blur,focus,load,resize,scroll,unload,click,dblclick," + "mousedown,mouseup,mousemove,mouseover,mouseout,change,select," + "submit,keydown,keypress,keyup,error").split(","), function (i, o) {
        E.fn[o] = function (f) {
            return f ? this.bind(o, f) : this.trigger(o);
        };
    });
    var x = false;
    // Cleanup functions for the document ready method
	if ( document.addEventListener ) {
		DOMContentLoaded = function() {
			document.removeEventListener( "DOMContentLoaded", DOMContentLoaded, false );
			jQuery.ready();
		};
	
	} else if ( document.attachEvent ) {
		DOMContentLoaded = function() {
			// Make sure body exists, at least, in case IE gets a little overzealous (ticket #5443).
			if ( document.readyState === "complete" ) {
				document.detachEvent( "onreadystatechange", DOMContentLoaded );
				jQuery.ready();
			}
		};
	}
	
    function bindReady() {
        if (x) return;
        x = true;
        /*
        if (E.browser.mozilla || E.browser.opera) document.addEventListener("DOMContentLoaded", E.ready, false);
        else if (E.browser.msie) {
            document.write("<scr" + "ipt id=__ie_init" + " defer=true " + " src='blank.js'><\/script>");
            var a = document.getElementById("__ie_init");
            a.parentNode.removeChild(a);
            if (a) a.onreadystatechange = function () {
                if (this.readyState != "complete") return;
                E.ready();
            };
            
            a = null;
        } else if (E.browser.safari) E.safariTimer = setInterval(function () {
            if (document.readyState == "loaded" || document.readyState == "complete") {
                clearInterval(E.safariTimer);
                E.safariTimer = null;
                E.ready();
            }
        }, 10);
        E.event.add(window, "load", E.ready);
        */
		// Catch cases where $(document).ready() is called after the
		// browser event has already occurred.
		if ( document.readyState === "complete" ) {
			return E.ready();
		}

		// Mozilla, Opera and webkit nightlies currently support this event
		if ( document.addEventListener ) {
			// Use the handy event callback
			document.addEventListener( "DOMContentLoaded", DOMContentLoaded, false );
			
			// A fallback to window.onload, that will always work
			window.addEventListener( "load", E.ready, false );

		// If IE event model is used
		} else if ( document.attachEvent ) {
			// ensure firing before onload,
			// maybe late but safe also for iframes
			document.attachEvent("onreadystatechange", DOMContentLoaded);
			
			// A fallback to window.onload, that will always work
			window.attachEvent( "onload", E.ready );

			// If IE and not a frame
			// continually check to see if the document is ready
			var toplevel = false;

			try {
				toplevel = window.frameElement == null;
			} catch(e) {}

			if ( document.documentElement.doScroll && toplevel ) {
				doScrollCheck();
			}
		}
    }
    
	// The DOM ready check for Internet Explorer
	function doScrollCheck() {
		if ( E.isReady ) {
			return;
		}
	
		try {
			// If IE is used, use the trick by Diego Perini
			// http://javascript.nwbox.com/IEContentLoaded/
			document.documentElement.doScroll("left");
		} catch( error ) {
			setTimeout( doScrollCheck, 1 );
			return;
		}
	
		// and execute any waiting functions
		E.ready();
	}
    
    E.fn.extend({
        load: function (g, d, c) {
            if (E.isFunction(g)) return this.bind("load", g);
            var e = g.indexOf(" ");
            if (e >= 0) {
                var i = g.slice(e, g.length);
                g = g.slice(0, e);
            }
            c = c || function () {};
            var f = "GET";
            if (d) if (E.isFunction(d)) {
                c = d;
                d = null;
            } else {
                d = E.param(d);
                f = "POST";
            }
            var h = this;
            E.ajax({
                url: g,
                type: f,
                data: d,
                complete: function (a, b) {
                    if (b == "success" || b == "notmodified") h.html(i ? E("<div/>").append(a.responseText.replace(/<script(.|\s)*?\/script>/g, "")).find(i) : a.responseText);
                    setTimeout(function () {
                        h.each(c, [a.responseText, b, a]);
                    }, 13);
                }
            });
            return this;
        },
        serialize: function () {
            return E.param(this.serializeArray());
        },
        serializeArray: function () {
            return this.map(function () {
                return E.nodeName(this, "form") ? E.makeArray(this.elements) : this;
            }).filter(function () {
                return this.name && !this.disabled && (this.checked || /select|textarea/i.test(this.nodeName) || /text|hidden|password/i.test(this.type));
            }).map(function (i, c) {
                var b = E(this).val();
                return b == null ? null : b.constructor == Array ? E.map(b, function (a, i) {
                    return {
                        name: c.name,
                        value: a
                    };
                }) : {
                    name: c.name,
                    value: b
                };
            }).get();
        }
    });
    E.each("ajaxStart,ajaxStop,ajaxComplete,ajaxError,ajaxSuccess,ajaxSend".split(","), function (i, o) {
        E.fn[o] = function (f) {
            return this.bind(o, f);
        };
    });
    var B = (new Date).getTime();
    E.extend({
        get: function (d, b, a, c) {
            if (E.isFunction(b)) {
                a = b;
                b = null;
            }
            return E.ajax({
                type: "GET",
                url: d,
                data: b,
                success: a,
                dataType: c
            });
        },
        getScript: function (b, a) {
            return E.get(b, null, a, "script");
        },
        getJSON: function (c, b, a) {
            return E.get(c, b, a, "json");
        },
        post: function (d, b, a, c) {
            if (E.isFunction(b)) {
                a = b;
                b = {};
            }
            return E.ajax({
                type: "POST",
                url: d,
                data: b,
                success: a,
                dataType: c
            });
        },
        ajaxSetup: function (a) {
            E.extend(E.ajaxSettings, a);
        },
        ajaxSettings: {
            global: true,
            type: "GET",
            timeout: 0,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            async: true,
            data: null
        },
        lastModified: {},
        ajax: function (s) {
            var f, jsre = /=(\?|%3F)/g,
                status, data;
            s = E.extend(true, s, E.extend(true, {}, E.ajaxSettings, s));
            if (s.data && s.processData && typeof s.data != "string") s.data = E.param(s.data);
            if (s.dataType == "jsonp") {
                if (s.type.toLowerCase() == "get") {
                    if (!s.url.match(jsre)) s.url += (s.url.match(/\?/) ? "&" : "?") + (s.jsonp || "callback") + "=?";
                } else if (!s.data || !s.data.match(jsre)) s.data = (s.data ? s.data + "&" : "") + (s.jsonp || "callback") + "=?";
                s.dataType = "json";
            }
            if (s.dataType == "json" && (s.data && s.data.match(jsre) || s.url.match(jsre))) {
                f = "jsonp" + B++;
                if (s.data) s.data = s.data.replace(jsre, "=" + f);
                s.url = s.url.replace(jsre, "=" + f);
                s.dataType = "script";
                window[f] = function (a) {
                    data = a;
                    success();
                    complete();
                    window[f] = undefined;
                    try {
                        delete window[f];
                    } catch (e) {}
                };
            }
            if (s.dataType == "script" && s.cache == null) s.cache = false;
            if (s.cache === false && s.type.toLowerCase() == "get") s.url += (s.url.match(/\?/) ? "&" : "?") + "_=" + (new Date()).getTime();
            if (s.data && s.type.toLowerCase() == "get") {
                s.url += (s.url.match(/\?/) ? "&" : "?") + s.data;
                s.data = null;
            }
            if (s.global && !E.active++) E.event.trigger("ajaxStart");
            if (!s.url.indexOf("http") && s.dataType == "script") {
                var h = document.getElementsByTagName("head")[0];
                var g = document.createElement("script");
                g.src = s.url;
                if (!f && (s.success || s.complete)) {
                    var j = false;
                    g.onload = g.onreadystatechange = function () {
                        if (!j && (!this.readyState || this.readyState == "loaded" || this.readyState == "complete")) {
                            j = true;
                            success();
                            complete();
                            h.removeChild(g);
                        }
                    };
                }
                h.appendChild(g);
                return;
            }
            var k = false;
            var i = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
            i.open(s.type, s.url, s.async);
            if (s.data) i.setRequestHeader("Content-Type", s.contentType);
            if (s.ifModified) i.setRequestHeader("If-Modified-Since", E.lastModified[s.url] || "Thu, 01 Jan 1970 00:00:00 GMT");
            i.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            if (s.beforeSend) s.beforeSend(i);
            if (s.global) E.event.trigger("ajaxSend", [i, s]);
            var c = function (a) {
                if (!k && i && (i.readyState == 4 || a == "timeout")) {
                    k = true;
                    if (d) {
                        clearInterval(d);
                        d = null;
                    }
                    status = a == "timeout" && "timeout" || !E.httpSuccess(i) && "error" || s.ifModified && E.httpNotModified(i, s.url) && "notmodified" || "success";
                    if (status == "success") {
                        try {
                            data = E.httpData(i, s.dataType);
                        } catch (e) {
                            status = "parsererror";
                        }
                    }
                    if (status == "success") {
                        var b;
                        try {
                            b = i.getResponseHeader("Last-Modified");
                        } catch (e) {}
                        if (s.ifModified && b) E.lastModified[s.url] = b;
                        if (!f) success();
                    } else E.handleError(s, i, status);
                    complete();
                    if (s.async) i = null;
                }
            };
            if (s.async) {
                var d = setInterval(c, 13);
                if (s.timeout > 0) setTimeout(function () {
                    if (i) {
                        i.abort();
                        if (!k) c("timeout");
                    }
                }, s.timeout);
            }
            try {
                i.send(s.data);
            } catch (e) {
                E.handleError(s, i, null, e);
            }
            if (!s.async) c();
            return i;

            function success() {
                if (s.success) s.success(data, status);
                if (s.global) E.event.trigger("ajaxSuccess", [i, s]);
            }

            function complete() {
                if (s.complete) s.complete(i, status);
                if (s.global) E.event.trigger("ajaxComplete", [i, s]);
                if (s.global && !--E.active) E.event.trigger("ajaxStop");
            }
        },
        handleError: function (s, a, b, e) {
            if (s.error) s.error(a, b, e);
            if (s.global) E.event.trigger("ajaxError", [a, s, e]);
        },
        active: 0,
        httpSuccess: function (r) {
            try {
                return !r.status && location.protocol == "file:" || (r.status >= 200 && r.status < 300) || r.status == 304 || E.browser.safari && r.status == undefined;
            } catch (e) {}
            return false;
        },
        httpNotModified: function (a, c) {
            try {
                var b = a.getResponseHeader("Last-Modified");
                return a.status == 304 || b == E.lastModified[c] || E.browser.safari && a.status == undefined;
            } catch (e) {}
            return false;
        },
        httpData: function (r, b) {
            var c = r.getResponseHeader("content-type");
            var d = b == "xml" || !b && c && c.indexOf("xml") >= 0;
            var a = d ? r.responseXML : r.responseText;
            if (d && a.documentElement.tagName == "parsererror") throw "parsererror";
            if (b == "script") E.globalEval(a);
            if (b == "json") a = eval("(" + a + ")");
            return a;
        },
        param: function (a) {
            var s = [];
            if (a.constructor == Array || a.jquery) E.each(a, function () {
                s.push(encodeURIComponent(this.name) + "=" + encodeURIComponent(this.value));
            });
            else for (var j in a) if (a[j] && a[j].constructor == Array) E.each(a[j], function () {
                s.push(encodeURIComponent(j) + "=" + encodeURIComponent(this));
            });
            else s.push(encodeURIComponent(j) + "=" + encodeURIComponent(a[j]));
            return s.join("&").replace(/%20/g, "+");
        }
    });
    E.fn.extend({
        show: function (b, a) {
            return b ? this.animate({
                height: "show",
                width: "show",
                opacity: "show"
            }, b, a) : this.filter(":hidden").each(function () {
                this.style.display = this.oldblock ? this.oldblock : "";
                if (E.css(this, "display") == "none") this.style.display = "block";
            }).end();
        },
        hide: function (b, a) {
            return b ? this.animate({
                height: "hide",
                width: "hide",
                opacity: "hide"
            }, b, a) : this.filter(":visible").each(function () {
                this.oldblock = this.oldblock || E.css(this, "display");
                if (this.oldblock == "none") this.oldblock = "block";
                this.style.display = "none";
            }).end();
        },
        _toggle: E.fn.toggle,
        toggle: function (a, b) {
            return E.isFunction(a) && E.isFunction(b) ? this._toggle(a, b) : a ? this.animate({
                height: "toggle",
                width: "toggle",
                opacity: "toggle"
            }, a, b) : this.each(function () {
                E(this)[E(this).is(":hidden") ? "show" : "hide"]();
            });
        },
        slideDown: function (b, a) {
            return this.animate({
                height: "show"
            }, b, a);
        },
        slideUp: function (b, a) {
            return this.animate({
                height: "hide"
            }, b, a);
        },
        slideToggle: function (b, a) {
            return this.animate({
                height: "toggle"
            }, b, a);
        },
        fadeIn: function (b, a) {
            return this.animate({
                opacity: "show"
            }, b, a);
        },
        fadeOut: function (b, a) {
            return this.animate({
                opacity: "hide"
            }, b, a);
        },
        fadeTo: function (c, a, b) {
            return this.animate({
                opacity: a
            }, c, b);
        },
        animate: function (k, i, h, g) {
            var j = E.speed(i, h, g);
            return this[j.queue === false ? "each" : "queue"](function () {
                j = E.extend({}, j);
                var f = E(this).is(":hidden"),
                    self = this;
                for (var p in k) {
                    if (k[p] == "hide" && f || k[p] == "show" && !f) return E.isFunction(j.complete) && j.complete.apply(this);
                    if (p == "height" || p == "width") {
                        j.display = E.css(this, "display");
                        j.overflow = this.style.overflow;
                    }
                }
                if (j.overflow != null) this.style.overflow = "hidden";
                j.curAnim = E.extend({}, k);
                E.each(k, function (c, a) {
                    var e = new E.fx(self, j, c);
                    if (/toggle|show|hide/.test(a)) e[a == "toggle" ? f ? "show" : "hide" : a](k);
                    else {
                        var b = a.toString().match(/^([+-]=)?([\d+-.]+)(.*)$/),
                            start = e.cur(true) || 0;
                        if (b) {
                            var d = parseFloat(b[2]),
                                unit = b[3] || "px";
                            if (unit != "px") {
                                self.style[c] = (d || 1) + unit;
                                start = ((d || 1) / e.cur(true)) * start;
                                self.style[c] = start + unit;
                            }
                            if (b[1]) d = ((b[1] == "-=" ? -1 : 1) * d) + start;
                            e.custom(start, d, unit);
                        } else e.custom(start, a, "");
                    }
                });
                return true;
            });
        },
        queue: function (a, b) {
            if (E.isFunction(a)) {
                b = a;
                a = "fx";
            }
            if (!a || (typeof a == "string" && !b)) return A(this[0], a);
            return this.each(function () {
                if (b.constructor == Array) A(this, a, b);
                else {
                    A(this, a).push(b);
                    if (A(this, a).length == 1) b.apply(this);
                }
            });
        },
        stop: function () {
            var a = E.timers;
            return this.each(function () {
                for (var i = 0; i < a.length; i++) if (a[i].elem == this) a.splice(i--, 1);
            }).dequeue();
        }
    });
    var A = function (b, c, a) {
        if (!b) return;
        var q = E.data(b, c + "queue");
        if (!q || a) q = E.data(b, c + "queue", a ? E.makeArray(a) : []);
        return q;
    };
    E.fn.dequeue = function (a) {
        a = a || "fx";
        return this.each(function () {
            var q = A(this, a);
            q.shift();
            if (q.length) q[0].apply(this);
        });
    };
    E.extend({
        speed: function (b, a, c) {
            var d = b && b.constructor == Object ? b : {
                complete: c || !c && a || E.isFunction(b) && b,
                duration: b,
                easing: c && a || a && a.constructor != Function && a
            };
            d.duration = (d.duration && d.duration.constructor == Number ? d.duration : {
                slow: 600,
                fast: 200
            }[d.duration]) || 400;
            d.old = d.complete;
            d.complete = function () {
                E(this).dequeue();
                if (E.isFunction(d.old)) d.old.apply(this);
            };
            return d;
        },
        easing: {
            linear: function (p, n, b, a) {
                return b + a * p;
            },
            swing: function (p, n, b, a) {
                return ((-Math.cos(p * Math.PI) / 2) + 0.5) * a + b;
            }
        },
        timers: [],
        fx: function (b, c, a) {
            this.options = c;
            this.elem = b;
            this.prop = a;
            if (!c.orig) c.orig = {};
        }
    });
    E.fx.prototype = {
        update: function () {
            if (this.options.step) this.options.step.apply(this.elem, [this.now, this]);
            (E.fx.step[this.prop] || E.fx.step._default)(this);
            if (this.prop == "height" || this.prop == "width") this.elem.style.display = "block";
        },
        cur: function (a) {
            if (this.elem[this.prop] != null && this.elem.style[this.prop] == null) return this.elem[this.prop];
            var r = parseFloat(E.curCSS(this.elem, this.prop, a));
            return r && r > -10000 ? r : parseFloat(E.css(this.elem, this.prop)) || 0;
        },
        custom: function (c, b, e) {
            this.startTime = (new Date()).getTime();
            this.start = c;
            this.end = b;
            this.unit = e || this.unit || "px";
            this.now = this.start;
            this.pos = this.state = 0;
            this.update();
            var f = this;

            function t() {
                return f.step();
            }
            t.elem = this.elem;
            E.timers.push(t);
            if (E.timers.length == 1) {
                var d = setInterval(function () {
                    var a = E.timers;
                    for (var i = 0; i < a.length; i++) if (!a[i]()) a.splice(i--, 1);
                    if (!a.length) clearInterval(d);
                }, 13);
            }
        },
        show: function () {
            this.options.orig[this.prop] = E.attr(this.elem.style, this.prop);
            this.options.show = true;
            this.custom(0, this.cur());
            if (this.prop == "width" || this.prop == "height") this.elem.style[this.prop] = "1px";
            E(this.elem).show();
        },
        hide: function () {
            this.options.orig[this.prop] = E.attr(this.elem.style, this.prop);
            this.options.hide = true;
            this.custom(this.cur(), 0);
        },
        step: function () {
            var t = (new Date()).getTime();
            if (t > this.options.duration + this.startTime) {
                this.now = this.end;
                this.pos = this.state = 1;
                this.update();
                this.options.curAnim[this.prop] = true;
                var a = true;
                for (var i in this.options.curAnim) if (this.options.curAnim[i] !== true) a = false;
                if (a) {
                    if (this.options.display != null) {
                        this.elem.style.overflow = this.options.overflow;
                        this.elem.style.display = this.options.display;
                        if (E.css(this.elem, "display") == "none") this.elem.style.display = "block";
                    }
                    if (this.options.hide) this.elem.style.display = "none";
                    if (this.options.hide || this.options.show) for (var p in this.options.curAnim) E.attr(this.elem.style, p, this.options.orig[p]);
                }
                if (a && E.isFunction(this.options.complete)) this.options.complete.apply(this.elem);
                return false;
            } else {
                var n = t - this.startTime;
                this.state = n / this.options.duration;
                this.pos = E.easing[this.options.easing || (E.easing.swing ? "swing" : "linear")](this.state, n, 0, 1, this.options.duration);
                this.now = this.start + ((this.end - this.start) * this.pos);
                this.update();
            }
            return true;
        }
    };
    E.fx.step = {
        scrollLeft: function (a) {
            a.elem.scrollLeft = a.now;
        },
        scrollTop: function (a) {
            a.elem.scrollTop = a.now;
        },
        opacity: function (a) {
            E.attr(a.elem.style, "opacity", a.now);
        },
        _default: function (a) {
            a.elem.style[a.prop] = a.now + a.unit;
        }
    };
    E.fn.offset = function () {
        var c = 0,
            top = 0,
            elem = this[0],
            results;
        if (elem) with(E.browser) {
            var b = E.css(elem, "position") == "absolute",
                parent = elem.parentNode,
                offsetParent = elem.offsetParent,
                doc = elem.ownerDocument,
                safari2 = safari && parseInt(version) < 522;
            if (elem.getBoundingClientRect) {
                box = elem.getBoundingClientRect();
                add(box.left + Math.max(doc.documentElement.scrollLeft, doc.body.scrollLeft), box.top + Math.max(doc.documentElement.scrollTop, doc.body.scrollTop));
                if (msie) {
                    var d = E("html").css("borderWidth");
                    d = (d == "medium" || E.boxModel && parseInt(version) >= 7) && 2 || d;
                    add(-d, -d);
                }
            } else {
                add(elem.offsetLeft, elem.offsetTop);
                while (offsetParent) {
                    add(offsetParent.offsetLeft, offsetParent.offsetTop);
                    if (mozilla && /^t[d|h]$/i.test(parent.tagName) || !safari2) d(offsetParent);
                    if (safari2 && !b && E.css(offsetParent, "position") == "absolute") b = true;
                    offsetParent = offsetParent.offsetParent;
                }
                while (parent.tagName && !/^body|html$/i.test(parent.tagName)) {
                    if (!/^inline|table-row.*$/i.test(E.css(parent, "display"))) add(-parent.scrollLeft, -parent.scrollTop);
                    if (mozilla && E.css(parent, "overflow") != "visible") d(parent);
                    parent = parent.parentNode;
                }
                if (safari2 && b) add(-doc.body.offsetLeft, -doc.body.offsetTop);
            }
            results = {
                top: top,
                left: c
            };
        }
        return results;

        function d(a) {
            add(E.css(a, "borderLeftWidth"), E.css(a, "borderTopWidth"));
        }

        function add(l, t) {
            c += parseInt(l) || 0;
            top += parseInt(t) || 0;
        }
    };
})();