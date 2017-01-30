(function(){function _PostRPC() {	// include all code here to inject easily 
	PostRPC = function(name, sendObj, receiveObj) { this._id = Math.floor(Math.random()*1000000);
 
		this._ns = '__PostRPC_' + name;
 
this._sendObj = sendObj;
 
this._calls = {};
 this._methods = {};
 if(!receiveObj) return;
	// send-only RPC if(receiveObj.emit) { receiveObj.on(this._ns, Util.delegate(this, '_receiveMessage'));
 } 
else { var _this = this;
 receiveObj.addEventListener("message", function(event) { var data = event.data && event.data[_this._ns];
	// everything is inside ns, to minimize conflicts with other message if(data) _this._receiveMessage(data);
 }, false);
 } 
};
 // public methods PostRPC.prototype.register = function(name, fun) { this._methods[name] = fun;
 };
 PostRPC.prototype.call = function(method, args, handler) { var callId;
 if(handler) { callId = Math.floor(Math.random()*1000000);
 this._calls[callId] = handler;
 } 
if(!args) args = [];
 this._sendMessage({ method: method, args: args, callId: callId, from: this._id });
 };
 // private methods for sending/receiving messages 
 PostRPC.prototype._sendMessage = function(message) { if(this._sendObj.emit) this._sendObj.emit(this._ns, message);
 else { // everything is inside ns, to minimize conflicts with other messages 
 	var temp = {};
 temp[this._ns] = message;
 this._sendObj.postMessage(temp, "*");
 } 
} 
PostRPC.prototype._receiveMessage = function(data) { if(data.method) { // message call 
	if(data.from == this._id) return; // we made this call, the other side should reply 
	if(!this._methods[data.method]) {	// not registered 
		Browser.log('PostRPC: no handler for '+data.method);
 return;
 } 
// pass returnHandler, used to send back the result 
var replyHandler;
 if(data.callId) { var _this = this;
 replyHandler = function() { var args = Array.prototype.slice.call(arguments);
	// arguments in real array 
	_this._sendMessage({ callId: data.callId, value: args });
 };
 } 
else { 
	replyHandler = function() {};
	// no result expected, use dummy handler 
} 
var dataArgs = Array.prototype.slice.call(data.args);
	// cannot modify data.args in Firefox 32, clone as workaround dataArgs.push(replyHandler);
 this._methods[data.method].apply(null, dataArgs);
 } 
else { // return value var c = this._calls[data.callId];
 delete this._calls[data.callId];
 if(!c) return;
	// return value for the other side, or no return handler 
	c.apply(null, data.value);
 } 
} 
}function injectedCode() { if(!navigator.geolocation) return;
	/* no geolocation API */ var prpc;
 // we replace geolocation methods with our own 
 // the real methods will be called by the content script (not by the page) 
 // so we dont need to keep them at all. 
 navigator.geolocation.getCurrentPosition = function(cb1, cb2, options) { 
 // create a PostRPC object only when getCurrentPosition is called. This 
 // avoids having our own postMessage handler on every page 
 if(!prpc) prpc = new PostRPC('page-content', document.defaultView, window);
 // call getNoisyPosition on the content-script 
 prpc.call('getNoisyPosition', [options], function(success, res) { 
 // call cb1 on success, cb2 on failure 
 var f = success ? cb1 : cb2;
 if(f) f(res);
 });
 };
 navigator.geolocation.watchPosition = function(cb1, cb2, options) { 
 // we don't install a real watch, just return the position once 
 // TODO: implement something closer to a watch 
 this.getCurrentPosition(cb1, cb2, options);
 return Math.floor(Math.random()*10000);
	// return random id, it's not really used 
};
 navigator.geolocation.clearWatch = function () { // nothing to do 
 };
 // remove script 
 var s = document.getElementById('__lg_script');
 if(s) s.remove();
	// DEMO: in demo injectCode is run directly so there's no script 
}_PostRPC();
 injectedCode();
})()
