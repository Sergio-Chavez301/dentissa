const loginView = document.querySelector('#login-view');
const dashboardView = document.querySelector('#dashboard-view');
const notice = document.querySelector('#notice');
const loginForm = document.querySelector('#login-form');
const appointmentForm = document.querySelector('#appointment-form');
const dateInput = document.querySelector('#date');
const timeSelect = document.querySelector('#time');
const serviceSelect = document.querySelector('#service');

let apiBaseUrl;
let token;

function showNotice(message, success = false) {
    notice.textContent = message;
    notice.classList.toggle('success', success);
    notice.hidden = !message;
}

function setView(isAuthenticated) {
    loginView.hidden = isAuthenticated;
    dashboardView.hidden = !isAuthenticated;
}

async function loadConfig() {
    try {
        const response = await fetch('./config.json', { cache: 'no-store' });
        if (!response.ok) {
            throw new Error('Falta configurar la URL HTTPS del servidor.');
        }

        const config = await response.json();
        const parsedUrl = new URL(config.apiBaseUrl);
        if (parsedUrl.protocol !== 'https:') {
            throw new Error('La URL del servidor debe usar HTTPS.');
        }

        apiBaseUrl = parsedUrl.toString().replace(/\/+$/, '');
    } catch (error) {
        showNotice(error.message || 'No se pudo cargar la configuración del servidor.');
        loginForm.querySelector('button').disabled = true;
    }
}

async function apiRequest(path, options = {}) {
    const headers = new Headers(options.headers || {});
    headers.set('Accept', 'application/json');
    if (options.body) {
        headers.set('Content-Type', 'application/json');
    }
    if (token) {
        headers.set('Authorization', `Bearer ${token}`);
    }

    const response = await fetch(`${apiBaseUrl}/api/mobile${path}`, {
        ...options,
        headers,
        cache: 'no-store',
    });
    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        if (response.status === 401 && token) {
            token = null;
            setView(false);
        }

        const validationMessage = Object.values(data.errors || {})
            .flat()
            .find(Boolean);
        throw new Error(validationMessage || data.message || 'No se pudo completar la operación.');
    }

    return data;
}

function fillSelect(select, items, placeholder, valueKey, labelKey) {
    select.replaceChildren();
    const firstOption = document.createElement('option');
    firstOption.value = '';
    firstOption.textContent = placeholder;
    select.append(firstOption);

    for (const item of items) {
        const option = document.createElement('option');
        option.value = item[valueKey];
        option.textContent = item[labelKey];
        select.append(option);
    }
}

function renderAppointments(appointments) {
    const list = document.querySelector('#appointments');
    list.replaceChildren();

    if (appointments.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'muted';
        empty.textContent = 'Todavía no tienes citas.';
        list.append(empty);
        return;
    }

    const labels = {
        en_espera: 'Pendiente de confirmación',
        confirmada: 'Confirmada',
        realizada: 'Realizada',
        cancelada: 'Cancelada',
        no_presento: 'No se presentó',
    };

    for (const appointment of appointments) {
        const item = document.createElement('article');
        item.className = 'appointment';

        const service = document.createElement('strong');
        service.textContent = appointment.service;

        const date = document.createElement('p');
        date.textContent = `${appointment.date} · ${appointment.time}`;

        const status = document.createElement('span');
        status.className = 'status';
        status.textContent = labels[appointment.status] || appointment.status;

        item.append(service, date, status);
        list.append(item);
    }
}

async function loadDashboard() {
    const dashboard = await apiRequest('/dashboard');
    document.querySelector('#patient-name').textContent = dashboard.patient.name;
    document.querySelector('#patient-contact').textContent = [
        dashboard.patient.email,
        dashboard.patient.phone,
    ].filter(Boolean).join(' · ');
    fillSelect(serviceSelect, dashboard.services, 'Selecciona un servicio', 'id', 'name');
    renderAppointments(dashboard.appointments);
    setView(true);
}

function localDateString(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

async function loadAvailability() {
    fillSelect(timeSelect, [], 'Cargando horarios...', 'time', 'time');
    timeSelect.disabled = true;

    if (!dateInput.value) {
        fillSelect(timeSelect, [], 'Selecciona primero una fecha', 'time', 'time');
        return;
    }

    try {
        const query = new URLSearchParams({ date: dateInput.value });
        const result = await apiRequest(`/availability?${query}`);
        fillSelect(timeSelect, result.slots.map((time) => ({ time })), 'Selecciona un horario', 'time', 'time');
        timeSelect.disabled = result.slots.length === 0;
        if (result.slots.length === 0) {
            fillSelect(timeSelect, [], 'No hay horarios disponibles', 'time', 'time');
        }
    } catch (error) {
        fillSelect(timeSelect, [], 'No se pudieron cargar los horarios', 'time', 'time');
        showNotice(error.message);
    }
}

loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    showNotice('');
    const button = loginForm.querySelector('button');
    button.disabled = true;

    try {
        const formData = new FormData(loginForm);
        const response = await apiRequest('/login', {
            method: 'POST',
            body: JSON.stringify({
                username: formData.get('username'),
                password: formData.get('password'),
            }),
        });
        token = response.token;
        await loadDashboard();
        loginForm.reset();
    } catch (error) {
        token = null;
        setView(false);
        showNotice(error.message);
    } finally {
        button.disabled = false;
    }
});

document.querySelector('#logout-button').addEventListener('click', async () => {
    try {
        await apiRequest('/logout', { method: 'POST' });
    } catch (error) {
        showNotice(error.message);
    } finally {
        token = null;
        setView(false);
    }
});

dateInput.min = localDateString(new Date());
dateInput.addEventListener('change', loadAvailability);

appointmentForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    showNotice('');
    const button = appointmentForm.querySelector('button');
    button.disabled = true;

    try {
        const formData = new FormData(appointmentForm);
        const result = await apiRequest('/appointments', {
            method: 'POST',
            body: JSON.stringify({
                service_id: Number(formData.get('service_id')),
                date: formData.get('date'),
                time: formData.get('time'),
            }),
        });
        showNotice(result.message, true);
        appointmentForm.reset();
        dateInput.min = localDateString(new Date());
        await loadAvailability();
        await loadDashboard();
    } catch (error) {
        showNotice(error.message);
        await loadAvailability();
    } finally {
        button.disabled = false;
    }
});

setView(false);
loadConfig();
