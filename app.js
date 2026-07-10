function erstellePagination(aktSeite, gesamt) {
    var html = "";

    if (aktSeite > 1) {
        html += "<button class='page-btn' data-seite='" + (aktSeite - 1) + "'>←</button>";
    } else {
        html += "<button disabled>←</button>";
    }

    var rand = 2;
    var letzteGezeigt = 0;
    for (var i = 1; i <= gesamt; i++) {
        if (i === 1 || i === gesamt || (i >= aktSeite - rand && i <= aktSeite + rand)) {
            if (letzteGezeigt !== 0 && i - letzteGezeigt > 1) {
                html += "<span class='page-dots'>…</span>";
            }
            if (i === aktSeite) {
                html += "<button class='page-btn aktiv' data-seite='" + i + "'>" + i + "</button>";
            } else {
                html += "<button class='page-btn' data-seite='" + i + "'>" + i + "</button>";
            }
            letzteGezeigt = i;
        }
    }

    if (aktSeite < gesamt) {
        html += "<button class='page-btn' data-seite='" + (aktSeite + 1) + "'>→</button>";
    } else {
        html += "<button disabled>→</button>";
    }

    $("#pagination").html(html);
}

function initModal(modalId, oeffnenId, schliessenId) {
    const modalFenster = document.getElementById(modalId);
    const oeffnenButton = document.getElementById(oeffnenId);
    const schliessenButton = document.getElementById(schliessenId);

    function schliesseModal() {
        modalFenster.classList.remove('show');
        const fehler = document.getElementById('modal-fehler');
        if (fehler) {
            fehler.style.display = 'none';
        }
    }

    oeffnenButton.addEventListener('click', function() {
        modalFenster.classList.add('show');
    });

    schliessenButton.addEventListener('click', schliesseModal);

    modalFenster.addEventListener('click', function(event) {
        if (event.target === modalFenster) {
            schliesseModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            schliesseModal();
        }
    });
}
