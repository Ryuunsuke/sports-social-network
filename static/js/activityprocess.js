document.addEventListener("DOMContentLoaded", function () {

    const selects = {};
    const addButton = document.getElementById("addButton2");
    const deleteButton = document.getElementById("deleteButton2");

    let clickedAction = '';

    document.getElementById('addButton2').addEventListener('click', function () {
        clickedAction = 'add';
    });
    document.getElementById('deleteButton2').addEventListener('click', function () {
        clickedAction = 'delete';
    });

    const atEl = document.getElementById('at');
    selects.at = new TomSelect(atEl, {
        create: true,
        maxItems: 1,
        options: atData,    // use atData directly here
        valueField: 'value',
        labelField: 'text',
        searchField: 'text'
    });

    function getSelectedText(selectInstance) {
        const value = selectInstance.getValue();
        if (!value) return '';

        const item = selectInstance.options[value];
        return item ? item.text : '';
    }

    // Check if all selects have values to show Add button
    function checkAtSelected() {
        const atText = getSelectedText(selects.at).trim();

        addButton.style.display = "none";
        deleteButton.style.display = "none";

        if (!atText) return;

        const payload = {
            activity: atText
        };

        fetch('../routes/checkactivity.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                deleteButton.style.display = "inline-block";
            } else if (atText) {
                addButton.style.display = "inline-block";
            }
            logMessage(data.message);
        })
        .catch(err => {
            logMessage("Error: " + err.message);
        });
    }

    // Lock select and load next options
    function lockSingleSelect(selectInstance) {
        selectInstance.on('change', function (value) {
            if (value.trim() !== '') {
                selectInstance.disable();
            } 
            checkAtSelected();
        });
    }

    lockSingleSelect(selects.at);

    // Reset button logic
    document.getElementById('resetButton2').addEventListener('click', function () {
        selects.at.clear();
        selects.at.enable();
        addButton.style.display = "none";
        logMessage('');
    });

    const log = document.getElementById('messageLog');

    function logMessage(message) {
        const log = document.getElementById('messageLog');
        log.innerText = '';      
        log.innerText = message;   
    }

    document.getElementById('id02').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const action = formData.get('action');

        const atText = selects.at.getItem(selects.at.getValue())?.innerText.trim() || '';
        const payload = {
            activity: atText,
            action: clickedAction
        };

        fetch('../routes/activityprocess.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            logMessage(data.message);
        })
        .catch(err => {
            logMessage(err.message);
        });
    });
});