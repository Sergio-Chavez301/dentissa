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
        date.textContent = `${formatDate(appointment.date)} · ${appointment.time}`;

        const status = document.createElement('span');
        status.className = 'status';
        status.textContent = labels[appointment.status] || appointment.status;

        item.append(service, date);
        if (appointment.treatment) {
            const treatment = document.createElement('p');
            treatment.className = 'muted';
            treatment.textContent = appointment.treatment;
            item.append(treatment);
        }
        item.append(status);
        list.append(item);
    }
}

function renderAppointmentSummary(counts) {
    const summary = document.querySelector('#appointment-summary');
    const states = [
        ['En espera', counts.en_espera],
        ['Confirmadas', counts.confirmada],
        ['Canceladas', counts.cancelada],
        ['No se presentó', counts.no_presento],
        ['Realizadas', counts.realizada],
    ];

    summary.replaceChildren();
    for (const [label, count] of states) {
        const card = document.createElement('article');
        card.className = 'summary-card';
        const title = document.createElement('span');
        title.textContent = label;
        const value = document.createElement('strong');
        value.textContent = count;
        card.append(title, value);
        summary.append(card);
    }
}

function formatDate(value) {
    if (!value) {
        return 'No registrada';
    }

    const [year, month, day] = value.split('-');
    return year && month && day ? `${day}/${month}/${year}` : value;
}

function renderPatientProfile(patient) {
    const profile = document.querySelector('#patient-profile');
    const fields = [
        ['Nombre completo', patient.name],
        ['Usuario', patient.username || 'No disponible'],
        ['Correo', patient.email || 'No registrado'],
        ['Teléfono', patient.phone || 'No registrado'],
        ['Fecha de nacimiento', formatDate(patient.birth_date)],
        ['Alergias', patient.allergies || 'Sin registro'],
        ['Enfermedades', patient.conditions || 'Sin registro'],
        ['Tratamientos actuales', patient.current_treatments || 'Sin registro'],
    ];

    profile.replaceChildren();
    for (const [label, value] of fields) {
        const group = document.createElement('div');
        const term = document.createElement('dt');
        term.textContent = label;
        const description = document.createElement('dd');
        description.textContent = value;
        group.append(term, description);
        profile.append(group);
    }
}

function renderTreatments(treatments) {
    const list = document.querySelector('#treatments');
    list.replaceChildren();

    if (treatments.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'muted';
        empty.textContent = 'Aún no hay tratamientos registrados en tu expediente.';
        list.append(empty);
        return;
    }

    for (const treatment of treatments) {
        const card = document.createElement('article');
        card.className = 'treatment';

        const heading = document.createElement('div');
        heading.className = 'treatment-heading';
        const name = document.createElement('h3');
        name.textContent = treatment.name;
        const status = document.createElement('span');
        status.className = 'status';
        status.textContent = treatment.status === 'activo' ? 'Activo' : 'Finalizado';
        heading.append(name, status);

        const progressLabel = document.createElement('div');
        progressLabel.className = 'progress-label';
        const progressText = document.createElement('span');
        progressText.textContent = 'Progreso del tratamiento';
        const progressValue = document.createElement('strong');
        progressValue.textContent = `${treatment.progress}%`;
        progressLabel.append(progressText, progressValue);

        const progress = document.createElement('progress');
        progress.className = 'progress';
        progress.max = 100;
        progress.value = treatment.progress;

        const historyTitle = document.createElement('h4');
        historyTitle.textContent = 'Citas realizadas';
        card.append(heading, progressLabel, progress, historyTitle);

        if (treatment.completed_appointments.length === 0) {
            const empty = document.createElement('p');
            empty.className = 'muted';
            empty.textContent = 'Todavía no hay citas realizadas asociadas a este tratamiento.';
            card.append(empty);
        } else {
            const history = document.createElement('ul');
            history.className = 'treatment-history';
            for (const appointment of treatment.completed_appointments) {
                const item = document.createElement('li');
                const service = document.createElement('span');
                service.textContent = appointment.service;
                const date = document.createElement('span');
                date.className = 'muted';
                date.textContent = `${formatDate(appointment.date)} · ${appointment.time}`;
                item.append(service, date);
                history.append(item);
            }
            card.append(history);
        }

        list.append(card);
    }
}

function renderOffers(offers) {
    const list = document.querySelector('#offers');
    list.replaceChildren();

    if (offers.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'muted';
        empty.textContent = 'No hay ofertas activas por el momento.';
        list.append(empty);
        return;
    }

    for (const offer of offers) {
        const card = document.createElement('article');
        card.className = 'offer';
        const tag = document.createElement('p');
        tag.className = 'eyebrow';
        tag.textContent = offer.tag || 'Promoción';
        const title = document.createElement('h3');
        title.textContent = offer.title;
        const description = document.createElement('p');
        description.className = 'muted';
        description.textContent = offer.description;
        card.append(tag, title, description);

        if (offer.price !== null) {
            const price = document.createElement('strong');
            price.textContent = new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN',
                maximumFractionDigits: 0,
            }).format(offer.price);
            card.append(price);
        }

        list.append(card);
    }
}

async function loadDashboard() {
    const dashboard = await apiRequest('/dashboard');
    document.querySelector('#patient-name').textContent = dashboard.patient.name;
    renderPatientProfile(dashboard.patient);
    renderAppointmentSummary(dashboard.appointment_counts);
    fillSelect(serviceSelect, dashboard.services, 'Selecciona un servicio', 'id', 'name');
    renderAppointments(dashboard.appointments);
    renderTreatments(dashboard.treatments);
    renderOffers(dashboard.offers);
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
