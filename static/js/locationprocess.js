document.addEventListener("DOMContentLoaded", function () {

    const selects = {};
    const addButton = document.getElementById("addButton1");
    const deleteButton = document.getElementById("deleteButton1");

    let clickedAction = '';

    document.getElementById('addButton1').addEventListener('click', function () {
        clickedAction = 'add';
    });
    document.getElementById('deleteButton1').addEventListener('click', function () {
        clickedAction = 'delete';
    });

    for (const [id, options] of Object.entries(selectData)) {
        selects[id] = new TomSelect(`#${id}`, {
            create: true,
            maxItems: 1,
            options: options
        });
    }

    selects.province.disable();
    selects.town.disable();

    function getSelectedText(selectInstance) {
        const value = selectInstance.getValue();
        if (!value) return '';

        const item = selectInstance.options[value];
        return item ? item.text : '';
    }

    // Check if all selects have values to show Add button
    function checkAllSelected() {
        const countryText = getSelectedText(selects.country).trim();
        const provinceText = getSelectedText(selects.province).trim();
        const townText = getSelectedText(selects.town).trim();

        addButton.style.display = "none";
        deleteButton.style.display = "none";

        if (!countryText) return;

        const payload = {
            country: countryText,
            province: provinceText,
            town: townText,
            level: townText ? 'town' : provinceText ? 'province' : 'country'
        };

        fetch('../routes/checklocations.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                deleteButton.style.display = "inline-block";
            } else if (countryText && provinceText && townText) {
                addButton.style.display = "inline-block";
            }
            logMessage(data.message);
        })
        .catch(err => {
            logMessage("Error: " + err.message);
        });
    }

    // Load provinces for a country (AJAX)
    function loadProvinces(countryName) {
        if (!countryName.trim()) {
            selects.province.clearOptions();
            selects.province.disable();
            selects.town.clearOptions();
            selects.town.disable();
            return;
        }

        fetch(`../routes/getlocations.php?country=${encodeURIComponent(countryName)}`)
            .then(response => response.json())
            .then(provinces => {
                selects.province.clearOptions();
                selects.town.clearOptions();
                selects.town.disable();
                provinces.forEach(province => {
                    selects.province.addOption({ value: province, text: province });
                });
                selects.province.enable();
            });
    }

    // Load towns for a province (AJAX)
    function loadTowns(provinceName) {
        if (!provinceName.trim()) {
            selects.town.clearOptions();
            selects.town.disable();
            return;
        }

        fetch(`../routes/getlocations.php?province=${encodeURIComponent(provinceName)}`)
            .then(response => response.json())
            .then(towns => {
                selects.town.clearOptions();
                towns.forEach(town => {
                    selects.town.addOption({ value: town, text: town });
                });
                selects.town.enable();
            });
    }

    // Lock select and load next options
    function lockOnSelect(selectInstance, nextId, loader) {
        selectInstance.on('change', function (value) {
            if (value.trim() !== '') {
                selectInstance.disable();

                if (loader && nextId && selects[nextId]) {
                    loader(value); // dynamically load next select options
                } else if (nextId && selects[nextId]) {
                    selects[nextId].enable();
                }
            } else {
                // If empty, clear and disable next selects
                if (nextId && selects[nextId]) {
                    selects[nextId].clear();
                    selects[nextId].disable();
                    if (nextId === 'province') {
                        selects.town.clear();
                        selects.town.disable();
                    } else if (nextId === 'town') {
                        // no further action
                    }
                }
            }
            checkAllSelected();
        });
    }

    // Use loaders for country->province and province->town
    lockOnSelect(selects.country, 'province', loadProvinces);
    lockOnSelect(selects.province, 'town', loadTowns);
    lockOnSelect(selects.town, null);
    

    // Reset button logic
    document.getElementById('resetButton1').addEventListener('click', function () {
        for (const id in selects) {
            selects[id].clear();
            selects[id].enable();
        }
        selects.province.disable();
        selects.town.disable();
        addButton.style.display = "none";
        logMessage('');
    });

    const log = document.getElementById('messageLog');

    function logMessage(message) {
        const log = document.getElementById('messageLog');
        log.innerText = '';      
        log.innerText = message;   
    }

    document.getElementById('id01').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const action = formData.get('action');

        const countryText = selects.country.getItem(selects.country.getValue())?.innerText.trim() || '';
        const provinceText = selects.province.getItem(selects.province.getValue())?.innerText.trim() || '';
        const townText = selects.town.getItem(selects.town.getValue())?.innerText.trim() || '';

        const payload = {
            country: countryText,
            province: provinceText,
            town: townText,
            action: clickedAction,
            level: townText ? 'town' : provinceText ? 'province' : 'country'
        };

        fetch('../routes/locationprocess.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            logMessage(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        })
        .catch(err => {
            logMessage(err.message);
        });
    });
});