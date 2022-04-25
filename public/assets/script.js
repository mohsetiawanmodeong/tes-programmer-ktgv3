const senggol = () => {
    const api = 'http://127.0.0.1:8000/api/senggol';
    const formulir = document.forms.daftar;
    const skmodal = new bootstrap.Modal(document.querySelector('#modal-sk'));
    const divtoast = document.querySelector('#toast-pesan');
    const pesantoast = new bootstrap.Toast(divtoast, {
        animation: true,
        autohide: true,
        delay: 2000
    });
    const showDialog = () => {
        skmodal.show();
    }
    const showToast = (pesan, error) => {
        document.querySelector('#toast-pesan .toast-body').innerHTML = pesan;
        if (error) {
            divtoast.classList.remove('bg-success');
            divtoast.classList.add('bg-danger');
        } else {
            divtoast.classList.remove('bg-danger');
            divtoast.classList.add('bg-success');
        }
        pesantoast.show();
    }
    async function postdata(url, data) {
        const response = await fetch(url, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: data
        });
        return response.json();
    }

    const sendData = (event) => {
        try {
            if (formulir.nama.value.length < 2 || formulir.alamat.value.length < 5) throw new Error("Ada isian wajib yang kosong.");
            if (isNaN(formulir.nik.value)) throw new Error("NIK harus diisikan dengan angka.");
            if (formulir.nik.value.length != 16) throw new Error("Jumlah karakter NIK tidak tepat 16 angka.");
            if (!formulir.setuju.checked) throw new Error("Anda belum setuju dengan syarat dan ketentuan.");

            const input = new FormData(formulir);
            postdata(api + 'index.php/daftar', input)
                .then(data => {
                    showToast(data.message, data.error == 0 ? false : true);
                })
                .catch((error) => {
                    showToast(error.message, true);
                })
        } catch (error) {
            showToast(error.message, true);
        }
        event.preventDefault();
    }
    const preventNaN = event => {
        if (
            event.getModifierState('Meta') ||
            event.getModifierState('Control') ||
            event.getModifierState('Alt')
        ) {
            return
        }
        const key = event.key;
        if ((key < '0' || key > '9') && key !== '.' && key !== '-') {
            event.preventDefault();
        }
    }
    formulir.addEventListener('submit', sendData);
    formulir.nik.addEventListener('keypress', preventNaN);

    showDialog();
};

window.addEventListener('load', senggol);
