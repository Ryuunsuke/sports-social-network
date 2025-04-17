let modalBtns = [...document.querySelectorAll(".signup-wrap")];
modalBtns.forEach(function (btn) {
	btn.onclick = function () {
		showLogin();
	};
});
let closeBtns = [...document.querySelectorAll(".close")];
closeBtns.forEach(function (btn) {
	btn.onclick = function () {
		closemodals();
	};
});