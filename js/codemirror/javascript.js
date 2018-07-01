! function (e) {
	"object" == typeof exports && "object" == typeof module ? e(require("../../lib/codemirror")) : "function" == typeof define && define.amd ? define(["../../lib/codemirror"], e) : e(CodeMirror)
}(function (e) {
	"use strict";
	e.defineMode("javascript", function (t, r) {
		function n(e, t, r) {
			return $e = e, Ce = r, t
		}

		function a(e, t) {
			var r = e.next();
			if ('"' == r || "'" == r) return t.tokenize = function (e) {
				return function (t, r) {
					var i, o = !1;
					if (Pe && "@" == t.peek() && t.match(We)) return r.tokenize = a, n("jsonld-keyword", "meta");
					for (; null != (i = t.next()) && (i != e || o);) o = !o && "\\" == i;
					return o || (r.tokenize = a), n("string", "string")
				}
			}(r), t.tokenize(e, t);
			if ("." == r && e.match(/^\d+(?:[eE][+\-]?\d+)?/)) return n("number", "number");
			if ("." == r && e.match("..")) return n("spread", "meta");
			if (/[\[\]{}\(\),;\:\.]/.test(r)) return n(r);
			if ("=" == r && e.eat(">")) return n("=>", "operator");
			if ("0" == r && e.match(/^(?:x[\da-f]+|o[0-7]+|b[01]+)n?/i)) return n("number", "number");
			if (/\d/.test(r)) return e.match(/^\d*(?:n|(?:\.\d*)?(?:[eE][+\-]?\d+)?)?/), n("number", "number");
			if ("/" == r) return e.eat("*") ? (t.tokenize = i, i(e, t)) : e.eat("/") ? (e.skipToEnd(), n("comment", "comment")) : Te(e, t, 1) ? (function (e) {
				for (var t, r = !1, n = !1; null != (t = e.next());) {
					if (!r) {
						if ("/" == t && !n) return;
						"[" == t ? n = !0 : n && "]" == t && (n = !1)
					}
					r = !r && "\\" == t
				}
			}(e), e.match(/^\b(([gimyus])(?![gimyus]*\2))+\b/), n("regexp", "string-2")) : (e.eat("="), n("operator", "operator", e.current()));
			if ("`" == r) return t.tokenize = o, o(e, t);
			if ("#" == r) return e.skipToEnd(), n("error", "error");
			if (He.test(r)) return ">" == r && t.lexical && ">" == t.lexical.type || (e.eat("=") ? "!" != r && "=" != r || e.eat("=") : /[<>*+\-]/.test(r) && (e.eat(r), ">" == r && e.eat(r))), n("operator", "operator", e.current());
			if (Ue.test(r)) {
				e.eatWhile(Ue);
				var c = e.current();
				if ("." != t.lastType) {
					if (Be.propertyIsEnumerable(c)) {
						var u = Be[c];
						return n(u.type, u.style, c)
					}
					if ("async" == c && e.match(/^(\s|\/\*.*?\*\/)*[\[\(\w]/, !1)) return n("async", "keyword", c)
				}
				return n("variable", "variable", c)
			}
		}

		function i(e, t) {
			for (var r, i = !1; r = e.next();) {
				if ("/" == r && i) {
					t.tokenize = a;
					break
				}
				i = "*" == r
			}
			return n("comment", "comment")
		}

		function o(e, t) {
			for (var r, i = !1; null != (r = e.next());) {
				if (!i && ("`" == r || "$" == r && e.eat("{"))) {
					t.tokenize = a;
					break
				}
				i = !i && "\\" == r
			}
			return n("quasi", "string-2", e.current())
		}

		function c(e, t) {
			t.fatArrowAt && (t.fatArrowAt = null);
			var r = e.string.indexOf("=>", e.start);
			if (!(r < 0)) {
				if (Ne) {
					var n = /:\s*(?:\w+(?:<[^>]*>|\[\])?|\{[^}]*\})\s*$/.exec(e.string.slice(e.start, r));
					n && (r = n.index)
				}
				for (var a = 0, i = !1, o = r - 1; o >= 0; --o) {
					var c = e.string.charAt(o),
						u = De.indexOf(c);
					if (u >= 0 && u < 3) {
						if (!a) {
							++o;
							break
						}
						if (0 == --a) {
							"(" == c && (i = !0);
							break
						}
					} else if (u >= 3 && u < 6) ++a;
					else if (Ue.test(c)) i = !0;
					else {
						if (/["'\/]/.test(c)) return;
						if (i && !a) {
							++o;
							break
						}
					}
				}
				i && !a && (t.fatArrowAt = o)
			}
		}

		function u(e, t, r, n, a, i) {
			this.indented = e, this.column = t, this.type = r, this.prev = a, this.info = i, null != n && (this.align = n)
		}

		function s(e, t) {
			for (var r = e.localVars; r; r = r.next)
				if (r.name == t) return !0;
			for (var n = e.context; n; n = n.prev)
				for (r = n.vars; r; r = r.next)
					if (r.name == t) return !0
		}

		function f() {
			for (var e = arguments.length - 1; e >= 0; e--) Ge.cc.push(arguments[e])
		}

		function l() {
			return f.apply(null, arguments), !0
		}

		function d(e, t) {
			for (var r = t; r; r = r.next)
				if (r.name == e) return !0;
			return !1
		}

		function p(e) {
			var t = Ge.state;
			if (Ge.marked = "def", t.context)
				if ("var" == t.lexical.info && t.context && t.context.block) {
					var n = m(e, t.context);
					if (null != n) return void(t.context = n)
				} else if (!d(e, t.localVars)) return void(t.localVars = new y(e, t.localVars));
			r.globalVars && !d(e, t.globalVars) && (t.globalVars = new y(e, t.globalVars))
		}

		function m(e, t) {
			if (t) {
				if (t.block) {
					var r = m(e, t.prev);
					return r ? r == t.prev ? t : new v(r, t.vars, !0) : null
				}
				return d(e, t.vars) ? t : new v(t.prev, new y(e, t.vars), !1)
			}
			return null
		}

		function k(e) {
			return "public" == e || "private" == e || "protected" == e || "abstract" == e || "readonly" == e
		}

		function v(e, t, r) {
			this.prev = e, this.vars = t, this.block = r
		}

		function y(e, t) {
			this.name = e, this.next = t
		}

		function w() {
			Ge.state.context = new v(Ge.state.context, Ge.state.localVars, !1), Ge.state.localVars = Je
		}

		function b() {
			Ge.state.context = new v(Ge.state.context, Ge.state.localVars, !0), Ge.state.localVars = null
		}

		function x() {
			Ge.state.localVars = Ge.state.context.vars, Ge.state.context = Ge.state.context.prev
		}

		function h(e, t) {
			var r = function () {
				var r = Ge.state,
					n = r.indented;
				if ("stat" == r.lexical.type) n = r.lexical.indented;
				else
					for (var a = r.lexical; a && ")" == a.type && a.align; a = a.prev) n = a.indented;
				r.lexical = new u(n, Ge.stream.column(), e, null, r.lexical, t)
			};
			return r.lex = !0, r
		}

		function g() {
			var e = Ge.state;
			e.lexical.prev && (")" == e.lexical.type && (e.indented = e.lexical.indented), e.lexical = e.lexical.prev)
		}

		function j(e) {
			function t(r) {
				return r == e ? l() : ";" == e || "}" == r || ")" == r || "]" == r ? f() : l(t)
			}
			return t
		}

		function M(e, t) {
			return "var" == e ? l(h("vardef", t), re, j(";"), g) : "keyword a" == e ? l(h("form"), z, M, g) : "keyword b" == e ? l(h("form"), M, g) : "keyword d" == e ? Ge.stream.match(/^\s*$/, !1) ? l() : l(h("stat"), T, j(";"), g) : "debugger" == e ? l(j(";")) : "{" == e ? l(h("}"), b, G, g, x) : ";" == e ? l() : "if" == e ? ("else" == Ge.state.lexical.info && Ge.state.cc[Ge.state.cc.length - 1] == g && Ge.state.cc.pop()(), l(h("form"), z, M, g, ce)) : "function" == e ? l(pe) : "for" == e ? l(h("form"), ue, M, g) : "class" == e || Ne && "interface" == t ? (Ge.marked = "keyword", l(h("form"), ve, g)) : "variable" == e ? Ne && "declare" == t ? (Ge.marked = "keyword", l(M)) : Ne && ("module" == t || "enum" == t || "type" == t) && Ge.stream.match(/^\s*\w/, !1) ? (Ge.marked = "keyword", "enum" == t ? l(ze) : "type" == t ? l(Q, j("operator"), Q, j(";")) : l(h("form"), ne, j("{"), h("}"), G, g, g)) : Ne && "namespace" == t ? (Ge.marked = "keyword", l(h("form"), A, G, g)) : Ne && "abstract" == t ? (Ge.marked = "keyword", l(M)) : l(h("stat"), N) : "switch" == e ? l(h("form"), z, j("{"), h("}", "switch"), b, G, g, g, x) : "case" == e ? l(A, j(":")) : "default" == e ? l(j(":")) : "catch" == e ? l(h("form"), w, V, M, g, x) : "export" == e ? l(h("stat"), xe, g) : "import" == e ? l(h("stat"), ge, g) : "async" == e ? l(M) : "@" == t ? l(A, M) : f(h("stat"), A, j(";"), g)
		}

		function V(e) {
			if ("(" == e) return l(me, j(")"))
		}

		function A(e, t) {
			return I(e, t, !1)
		}

		function E(e, t) {
			return I(e, t, !0)
		}

		function z(e) {
			return "(" != e ? f() : l(h(")"), A, j(")"), g)
		}

		function I(e, t, r) {
			if (Ge.state.fatArrowAt == Ge.stream.start) {
				var n = r ? S : P;
				if ("(" == e) return l(w, h(")"), D(me, ")"), g, j("=>"), n, x);
				if ("variable" == e) return f(w, ne, j("=>"), n, x)
			}
			var a = r ? C : $;
			return Fe.hasOwnProperty(e) ? l(a) : "function" == e ? l(pe, a) : "class" == e || Ne && "interface" == t ? (Ge.marked = "keyword", l(h("form"), ke, g)) : "keyword c" == e || "async" == e ? l(r ? E : A) : "(" == e ? l(h(")"), T, j(")"), g, a) : "operator" == e || "spread" == e ? l(r ? E : A) : "[" == e ? l(h("]"), Ee, g, a) : "{" == e ? F(B, "}", null, a) : "quasi" == e ? f(q, a) : "new" == e ? l(function (e) {
				return function (t) {
					return "." == t ? l(e ? function (e, t) {
						if ("target" == t) return Ge.marked = "keyword", l(C)
					} : function (e, t) {
						if ("target" == t) return Ge.marked = "keyword", l($)
					}) : "variable" == t && Ne ? l(_, e ? C : $) : f(e ? E : A)
				}
			}(r)) : "import" == e ? l(A) : l()
		}

		function T(e) {
			return e.match(/[;\}\)\],]/) ? f() : f(A)
		}

		function $(e, t) {
			return "," == e ? l(A) : C(e, t, !1)
		}

		function C(e, t, r) {
			var n = 0 == r ? $ : C,
				a = 0 == r ? A : E;
			return "=>" == e ? l(w, r ? S : P, x) : "operator" == e ? /\+\+|--/.test(t) || Ne && "!" == t ? l(n) : Ne && "<" == t && Ge.stream.match(/^([^>]|<.*?>)*>\s*\(/, !1) ? l(h(">"), D(Q, ">"), g, n) : "?" == t ? l(A, j(":"), a) : l(a) : "quasi" == e ? f(q, n) : ";" != e ? "(" == e ? F(E, ")", "call", n) : "." == e ? l(U, n) : "[" == e ? l(h("]"), T, j("]"), g, n) : Ne && "as" == t ? (Ge.marked = "keyword", l(Q, n)) : "regexp" == e ? (Ge.state.lastType = Ge.marked = "operator", Ge.stream.backUp(Ge.stream.pos - Ge.stream.start - 1), l(a)) : void 0 : void 0
		}

		function q(e, t) {
			return "quasi" != e ? f() : "${" != t.slice(t.length - 2) ? l(q) : l(A, O)
		}

		function O(e) {
			if ("}" == e) return Ge.marked = "string-2", Ge.state.tokenize = o, l(q)
		}

		function P(e) {
			return c(Ge.stream, Ge.state), f("{" == e ? M : A)
		}

		function S(e) {
			return c(Ge.stream, Ge.state), f("{" == e ? M : E)
		}

		function N(e) {
			return ":" == e ? l(g, M) : f($, j(";"), g)
		}

		function U(e) {
			if ("variable" == e) return Ge.marked = "property", l()
		}

		function B(e, t) {
			if ("async" == e) return Ge.marked = "property", l(B);
			if ("variable" == e || "keyword" == Ge.style) {
				if (Ge.marked = "property", "get" == t || "set" == t) return l(H);
				var r;
				return Ne && Ge.state.fatArrowAt == Ge.stream.start && (r = Ge.stream.match(/^\s*:\s*/, !1)) && (Ge.state.fatArrowAt = Ge.stream.pos + r[0].length), l(W)
			}
			return "number" == e || "string" == e ? (Ge.marked = Pe ? "property" : Ge.style + " property", l(W)) : "jsonld-keyword" == e ? l(W) : Ne && k(t) ? (Ge.marked = "keyword", l(B)) : "[" == e ? l(A, J, j("]"), W) : "spread" == e ? l(E, W) : "*" == t ? (Ge.marked = "keyword", l(B)) : ":" == e ? f(W) : void 0
		}

		function H(e) {
			return "variable" != e ? f(W) : (Ge.marked = "property", l(pe))
		}

		function W(e) {
			return ":" == e ? l(E) : "(" == e ? f(pe) : void 0
		}

		function D(e, t, r) {
			function n(a, i) {
				if (r ? r.indexOf(a) > -1 : "," == a) {
					var o = Ge.state.lexical;
					return "call" == o.info && (o.pos = (o.pos || 0) + 1), l(function (r, n) {
						return r == t || n == t ? f() : f(e)
					}, n)
				}
				return a == t || i == t ? l() : l(j(t))
			}
			return function (r, a) {
				return r == t || a == t ? l() : f(e, n)
			}
		}

		function F(e, t, r) {
			for (var n = 3; n < arguments.length; n++) Ge.cc.push(arguments[n]);
			return l(h(t, r), D(e, t), g)
		}

		function G(e) {
			return "}" == e ? l() : f(M, G)
		}

		function J(e, t) {
			if (Ne) {
				if (":" == e) return l(Q);
				if ("?" == t) return l(J)
			}
		}

		function K(e) {
			if (Ne && ":" == e) return Ge.stream.match(/^\s*\w+\s+is\b/, !1) ? l(A, L, Q) : l(Q)
		}

		function L(e, t) {
			if ("is" == t) return Ge.marked = "keyword", l()
		}

		function Q(e, t) {
			return "keyof" == t || "typeof" == t ? (Ge.marked = "keyword", l("keyof" == t ? Q : E)) : "variable" == e || "void" == t ? (Ge.marked = "type", l(Z)) : "string" == e || "number" == e || "atom" == e ? l(Z) : "[" == e ? l(h("]"), D(Q, "]", ","), g, Z) : "{" == e ? l(h("}"), D(X, "}", ",;"), g, Z) : "(" == e ? l(D(Y, ")"), R) : "<" == e ? l(D(Q, ">"), Q) : void 0
		}

		function R(e) {
			if ("=>" == e) return l(Q)
		}

		function X(e, t) {
			return "variable" == e || "keyword" == Ge.style ? (Ge.marked = "property", l(X)) : "?" == t ? l(X) : ":" == e ? l(Q) : "[" == e ? l(A, J, j("]"), X) : void 0
		}

		function Y(e, t) {
			return "variable" == e && Ge.stream.match(/^\s*[?:]/, !1) || "?" == t ? l(Y) : ":" == e ? l(Q) : f(Q)
		}

		function Z(e, t) {
			return "<" == t ? l(h(">"), D(Q, ">"), g, Z) : "|" == t || "." == e || "&" == t ? l(Q) : "[" == e ? l(j("]"), Z) : "extends" == t || "implements" == t ? (Ge.marked = "keyword", l(Q)) : void 0
		}

		function _(e, t) {
			if ("<" == t) return l(h(">"), D(Q, ">"), g, Z)
		}

		function ee() {
			return f(Q, te)
		}

		function te(e, t) {
			if ("=" == t) return l(Q)
		}

		function re(e, t) {
			return "enum" == t ? (Ge.marked = "keyword", l(ze)) : f(ne, J, ie, oe)
		}

		function ne(e, t) {
			return Ne && k(t) ? (Ge.marked = "keyword", l(ne)) : "variable" == e ? (p(t), l()) : "spread" == e ? l(ne) : "[" == e ? F(ne, "]") : "{" == e ? F(ae, "}") : void 0
		}

		function ae(e, t) {
			return "variable" != e || Ge.stream.match(/^\s*:/, !1) ? ("variable" == e && (Ge.marked = "property"), "spread" == e ? l(ne) : "}" == e ? f() : l(j(":"), ne, ie)) : (p(t), l(ie))
		}

		function ie(e, t) {
			if ("=" == t) return l(E)
		}

		function oe(e) {
			if ("," == e) return l(re)
		}

		function ce(e, t) {
			if ("keyword b" == e && "else" == t) return l(h("form", "else"), M, g)
		}

		function ue(e, t) {
			return "await" == t ? l(ue) : "(" == e ? l(h(")"), se, j(")"), g) : void 0
		}

		function se(e) {
			return "var" == e ? l(re, j(";"), le) : ";" == e ? l(le) : "variable" == e ? l(fe) : f(A, j(";"), le)
		}

		function fe(e, t) {
			return "in" == t || "of" == t ? (Ge.marked = "keyword", l(A)) : l($, le)
		}

		function le(e, t) {
			return ";" == e ? l(de) : "in" == t || "of" == t ? (Ge.marked = "keyword", l(A)) : f(A, j(";"), de)
		}

		function de(e) {
			")" != e && l(A)
		}

		function pe(e, t) {
			return "*" == t ? (Ge.marked = "keyword", l(pe)) : "variable" == e ? (p(t), l(pe)) : "(" == e ? l(w, h(")"), D(me, ")"), g, K, M, x) : Ne && "<" == t ? l(h(">"), D(ee, ">"), g, pe) : void 0
		}

		function me(e, t) {
			return "@" == t && l(A, me), "spread" == e ? l(me) : Ne && k(t) ? (Ge.marked = "keyword", l(me)) : f(ne, J, ie)
		}

		function ke(e, t) {
			return "variable" == e ? ve(e, t) : ye(e, t)
		}

		function ve(e, t) {
			if ("variable" == e) return p(t), l(ye)
		}

		function ye(e, t) {
			return "<" == t ? l(h(">"), D(ee, ">"), g, ye) : "extends" == t || "implements" == t || Ne && "," == e ? ("implements" == t && (Ge.marked = "keyword"), l(Ne ? Q : A, ye)) : "{" == e ? l(h("}"), we, g) : void 0
		}

		function we(e, t) {
			return "async" == e || "variable" == e && ("static" == t || "get" == t || "set" == t || Ne && k(t)) && Ge.stream.match(/^\s+[\w$\xa1-\uffff]/, !1) ? (Ge.marked = "keyword", l(we)) : "variable" == e || "keyword" == Ge.style ? (Ge.marked = "property", l(Ne ? be : pe, we)) : "[" == e ? l(A, J, j("]"), Ne ? be : pe, we) : "*" == t ? (Ge.marked = "keyword", l(we)) : ";" == e ? l(we) : "}" == e ? l() : "@" == t ? l(A, we) : void 0
		}

		function be(e, t) {
			return "?" == t ? l(be) : ":" == e ? l(Q, ie) : "=" == t ? l(E) : f(pe)
		}

		function xe(e, t) {
			return "*" == t ? (Ge.marked = "keyword", l(Ae, j(";"))) : "default" == t ? (Ge.marked = "keyword", l(A, j(";"))) : "{" == e ? l(D(he, "}"), Ae, j(";")) : f(M)
		}

		function he(e, t) {
			return "as" == t ? (Ge.marked = "keyword", l(j("variable"))) : "variable" == e ? f(E, he) : void 0
		}

		function ge(e) {
			return "string" == e ? l() : "(" == e ? f(A) : f(je, Me, Ae)
		}

		function je(e, t) {
			return "{" == e ? F(je, "}") : ("variable" == e && p(t), "*" == t && (Ge.marked = "keyword"), l(Ve))
		}

		function Me(e) {
			if ("," == e) return l(je, Me)
		}

		function Ve(e, t) {
			if ("as" == t) return Ge.marked = "keyword", l(je)
		}

		function Ae(e, t) {
			if ("from" == t) return Ge.marked = "keyword", l(A)
		}

		function Ee(e) {
			return "]" == e ? l() : f(D(E, "]"))
		}

		function ze() {
			return f(h("form"), ne, j("{"), h("}"), D(Ie, "}"), g, g)
		}

		function Ie() {
			return f(ne, ie)
		}

		function Te(e, t, r) {
			return t.tokenize == a && /^(?:operator|sof|keyword [bcd]|case|new|export|default|spread|[\[{}\(,;:]|=>)$/.test(t.lastType) || "quasi" == t.lastType && /\{\s*$/.test(e.string.slice(0, e.pos - (r || 0)))
		}
		var $e, Ce, qe = t.indentUnit,
			Oe = r.statementIndent,
			Pe = r.jsonld,
			Se = r.json || Pe,
			Ne = r.typescript,
			Ue = r.wordCharacters || /[\w$\xa1-\uffff]/,
			Be = function () {
				function e(e) {
					return {
						type: e,
						style: "keyword"
					}
				}
				var t = e("keyword a"),
					r = e("keyword b"),
					n = e("keyword c"),
					a = e("keyword d"),
					i = e("operator"),
					o = {
						type: "atom",
						style: "atom"
					};
				return {
					if: e("if"),
					while: t,
					with: t,
					else: r,
					do: r,
					try: r,
					finally: r,
					return: a,
					break: a,
					continue: a,
					new: e("new"),
					delete: n,
					void: n,
					throw: n,
					debugger: e("debugger"),
					var: e("var"),
					const: e("var"),
					let: e("var"),
					function: e("function"),
					catch: e("catch"),
					for: e("for"),
					switch: e("switch"),
					case: e("case"),
					default: e("default"),
					in: i,
					typeof: i,
					instanceof: i,
					true: o,
					false: o,
					null: o,
					undefined: o,
					NaN: o,
					Infinity: o,
					this: e("this"),
					class: e("class"),
					super: e("atom"),
					yield: n,
					export: e("export"),
					import: e("import"),
					extends: n,
					await: n
				}
			}(),
			He = /[+\-*&%=<>!?|~^@]/,
			We = /^@(context|id|value|language|type|container|list|set|reverse|index|base|vocab|graph)"/,
			De = "([{}])",
			Fe = {
				atom: !0,
				number: !0,
				variable: !0,
				string: !0,
				regexp: !0,
				this: !0,
				"jsonld-keyword": !0
			},
			Ge = {
				state: null,
				column: null,
				marked: null,
				cc: null
			},
			Je = new y("this", new y("arguments", null));
		return x.lex = !0, g.lex = !0, {
			startState: function (e) {
				var t = {
					tokenize: a,
					lastType: "sof",
					cc: [],
					lexical: new u((e || 0) - qe, 0, "block", !1),
					localVars: r.localVars,
					context: r.localVars && new v(null, null, !1),
					indented: e || 0
				};
				return r.globalVars && "object" == typeof r.globalVars && (t.globalVars = r.globalVars), t
			},
			token: function (e, t) {
				if (e.sol() && (t.lexical.hasOwnProperty("align") || (t.lexical.align = !1), t.indented = e.indentation(), c(e, t)), t.tokenize != i && e.eatSpace()) return null;
				var r = t.tokenize(e, t);
				return "comment" == $e ? r : (t.lastType = "operator" != $e || "++" != Ce && "--" != Ce ? $e : "incdec", function (e, t, r, n, a) {
					var i = e.cc;
					for (Ge.state = e, Ge.stream = a, Ge.marked = null, Ge.cc = i, Ge.style = t, e.lexical.hasOwnProperty("align") || (e.lexical.align = !0);;)
						if ((i.length ? i.pop() : Se ? A : M)(r, n)) {
							for (; i.length && i[i.length - 1].lex;) i.pop()();
							return Ge.marked ? Ge.marked : "variable" == r && s(e, n) ? "variable-2" : t
						}
				}(t, r, $e, Ce, e))
			},
			indent: function (t, n) {
				if (t.tokenize == i) return e.Pass;
				if (t.tokenize != a) return 0;
				var o, c = n && n.charAt(0),
					u = t.lexical;
				if (!/^\s*else\b/.test(n))
					for (var s = t.cc.length - 1; s >= 0; --s) {
						var f = t.cc[s];
						if (f == g) u = u.prev;
						else if (f != ce) break
					}
				for (;
					("stat" == u.type || "form" == u.type) && ("}" == c || (o = t.cc[t.cc.length - 1]) && (o == $ || o == C) && !/^[,\.=+\-*:?[\(]/.test(n));) u = u.prev;
				Oe && ")" == u.type && "stat" == u.prev.type && (u = u.prev);
				var l = u.type,
					d = c == l;
				return "vardef" == l ? u.indented + ("operator" == t.lastType || "," == t.lastType ? u.info.length + 1 : 0) : "form" == l && "{" == c ? u.indented : "form" == l ? u.indented + qe : "stat" == l ? u.indented + (function (e, t) {
					return "operator" == e.lastType || "," == e.lastType || He.test(t.charAt(0)) || /[,.]/.test(t.charAt(0))
				}(t, n) ? Oe || qe : 0) : "switch" != u.info || d || 0 == r.doubleIndentSwitch ? u.align ? u.column + (d ? 0 : 1) : u.indented + (d ? 0 : qe) : u.indented + (/^(?:case|default)\b/.test(n) ? qe : 2 * qe)
			},
			electricInput: /^\s*(?:case .*?:|default:|\{|\})$/,
			blockCommentStart: Se ? null : "/*",
			blockCommentEnd: Se ? null : "*/",
			blockCommentContinue: Se ? null : " * ",
			lineComment: Se ? null : "//",
			fold: "brace",
			closeBrackets: "()[]{}''\"\"``",
			helperType: Se ? "json" : "javascript",
			jsonldMode: Pe,
			jsonMode: Se,
			expressionAllowed: Te,
			skipExpression: function (e) {
				var t = e.cc[e.cc.length - 1];
				t != A && t != E || e.cc.pop()
			}
		}
	}), e.registerHelper("wordChars", "javascript", /[\w$]/), e.defineMIME("text/javascript", "javascript"), e.defineMIME("text/ecmascript", "javascript"), e.defineMIME("application/javascript", "javascript"), e.defineMIME("application/x-javascript", "javascript"), e.defineMIME("application/ecmascript", "javascript"), e.defineMIME("application/json", {
		name: "javascript",
		json: !0
	}), e.defineMIME("application/x-json", {
		name: "javascript",
		json: !0
	}), e.defineMIME("application/ld+json", {
		name: "javascript",
		jsonld: !0
	}), e.defineMIME("text/typescript", {
		name: "javascript",
		typescript: !0
	}), e.defineMIME("application/typescript", {
		name: "javascript",
		typescript: !0
	})
});
