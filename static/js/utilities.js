window.onclick = function(event) {
    if (event.target.classList.contains('loginmodal')) {
        document.getElementById('id01').style.display = "none";
    }
    if (event.target.classList.contains('registermodal')) {
        document.getElementById('id02').style.display = "none";
    }
}

function closemodals(){
    document.getElementById('id01').style.display='none';
    document.getElementById('id02').style.display='none';
    document.body.classList.remove('modal-open');
}

function showRegister() {
    document.getElementById('id01').style.display = 'none'; // Hide login modal
    document.getElementById('id02').style.display = 'block'; // Show register modal
    document.body.classList.add('modal-open');
}

function showLogin() {
    document.getElementById('id02').style.display = 'none'; // Hide register modal
    document.getElementById('id01').style.display = 'block'; // Show login modal
    document.body.classList.add('modal-open');
}

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

document.getElementById('RCountry').addEventListener('change', function () {
    const countryId = this.value;
    const provinceSelect = document.getElementById('RProvince');
    const townSelect = document.getElementById('RTown');

    // Show loading and disable province and town dropdowns
    provinceSelect.innerHTML = '<option value="">--Loading Provinces--</option>';
    provinceSelect.disabled = true;

    townSelect.innerHTML = '<option value="">--Select Province First--</option>';
    townSelect.disabled = true;

    if (countryId) {
        fetch(`index.php?country_id=${countryId}`) // replace your_php_file.php with your filename or use basename(__FILE__)
            .then(response => response.json())
            .then(data => {
                provinceSelect.innerHTML = '<option value="">--Select Province--</option>';
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
                provinceSelect.disabled = false; // enable now that data is loaded
            })
            .catch(() => {
                provinceSelect.innerHTML = '<option value="">--Error loading provinces--</option>';
            });
    } else {
        provinceSelect.innerHTML = '<option value="">--Select Country First--</option>';
        provinceSelect.disabled = true;
    }
});

document.getElementById('RProvince').addEventListener('change', function () {
    const provinceId = this.value;
    const townSelect = document.getElementById('RTown');

    townSelect.innerHTML = '<option value="">--Loading Towns--</option>';
    townSelect.disabled = true;

    if (provinceId) {
        fetch(`index.php?province_id=${provinceId}`)
            .then(response => response.json())
            .then(data => {
                townSelect.innerHTML = '<option value="">--Select Town--</option>';
                data.forEach(town => {
                    const option = document.createElement('option');
                    option.value = town.id;
                    option.textContent = town.name;
                    townSelect.appendChild(option);
                });
                townSelect.disabled = false;
            })
            .catch(() => {
                townSelect.innerHTML = '<option value="">--Error loading towns--</option>';
            });
    } else {
        townSelect.innerHTML = '<option value="">--Select Province First--</option>';
        townSelect.disabled = true;
    }
});
