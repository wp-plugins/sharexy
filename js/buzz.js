var buzz = function(block_id,params,global) {

    this.block = global.document.getElementById(block_id);
    this.global = global;
    this.speed = 35;
    this.amplitude = 10;
    //this.amplitude_speed = 2;
    this.p = 0;
    this.period = 2;
    this.orient = params;
    this.popup = global.document.getElementById('shr_popup_'+block_id);
    this.init();


    /*this.orient = 'h';
if (this.block.offsetHeight >= this.block.offsetWidth) { this.orient = 'v'; }
*/


};
buzz.prototype.init = function () {
    this.p = this.p + 1;
    var block = this.block, s = 0, sp = 0, move_left, move_right, move_center, speed = this.speed, amplitude = this.amplitude, self = this, p = this.p, period = this.period;
    block.style.position = "relative";
    block.style.top = "0px";
    block.style.left = "0px";

    if (self.popup !== null) { if (self.orient == "h") { sp = self.popup.offsetLeft; } else { sp = self.popup.offsetTop;} }

    move_right = setInterval(function() {
        s = s + 5; sp = sp + 5;
        if (self.orient == "h") { block.style.left = s + 'px'; if (self.popup !== null) {self.popup.style.left= sp + 'px';} } else { block.style.top = s + 'px'; if (self.popup !== null) {self.popup.style.top= sp + 'px';}}

        if (s >= amplitude) {
            clearInterval(move_right);
            move_left = setInterval(function() {
                s = s - 5; sp = sp - 5;
                if (self.orient == "h") { block.style.left = s + 'px';if (self.popup !== null) {self.popup.style.left= sp + 'px';} } else { block.style.top = s + 'px'; if (self.popup !== null) {self.popup.style.top= sp + 'px';} }
                if (s <= -amplitude) {
                    clearInterval(move_left);
                    move_center = setInterval(function() {
                        s = s + 5; sp = sp + 5;
                        if (self.orient == "h") { block.style.left = s + 'px'; if (self.popup !== null) {self.popup.style.left= sp + 'px';}} else { block.style.top = s + 'px'; if (self.popup !== null) {self.popup.style.top= sp + 'px';}}
                        if (s == 0) {
                            clearInterval(move_center);
                            if (p < period) { self.init(); }
                        }
                    }, speed);
                }
                         /* if (s <= 0) {
clearInterval(move_left);
if (p < period) { self.init(); }

}*/
            }, speed);

        }

    }, speed);

};