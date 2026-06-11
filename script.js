/* ============================================================
   HUBTOWN NIGERIA — map explorer interactions
   ============================================================ */

// Marker x/y are percentages of the map artwork (SVG viewBox 0 0 800 680),
// derived from lon/lat via x = (lon - 2.5) * 57, y = (14.2 - lat) * 57.
const PROJECTS = [
  { name: "Eko Crest Towers",        region: "lagos", city: "Victoria Island, Lagos", x: 7.3,  y: 65.9, status: "Now Selling",        type: "Residential", year: 2026, units: 214, desc: "Twin residential towers rising above the Lagos lagoon, with private marinas and skyline terraces." },
  { name: "Lekki Pearl Residences",  region: "lagos", city: "Lekki Phase 1, Lagos",   x: 9.4,  y: 66.5, status: "Under Construction", type: "Residential", year: 2027, units: 168, desc: "Waterfront living along the Lekki corridor — serviced apartments wrapped in tropical gardens." },
  { name: "Marina Gate",             region: "lagos", city: "Lagos Island, Lagos",    x: 6.0,  y: 65.3, status: "Sold Out",            type: "Commercial",  year: 2024, units: 96,  desc: "A landmark commercial address at the gateway to Marina, home to Nigeria's leading enterprises." },
  { name: "Ikeja Heights",           region: "lagos", city: "Ikeja GRA, Lagos",       x: 6.5,  y: 62.5, status: "Now Selling",        type: "Mixed Use",   year: 2026, units: 240, desc: "Residences, retail and workspaces minutes from Murtala Muhammed International Airport." },
  { name: "Maitama Skyline",         region: "abuja", city: "Maitama, Abuja FCT",     x: 35.8, y: 42.1, status: "Now Selling",        type: "Residential", year: 2026, units: 132, desc: "Diplomatic-district living with panoramic views of Aso Rock and the capital's green belt." },
  { name: "Garki Central Square",    region: "abuja", city: "Garki, Abuja FCT",       x: 36.3, y: 43.8, status: "Under Construction", type: "Commercial",  year: 2027, units: 80,  desc: "Grade-A offices and a civic plaza at the commercial heart of the Federal Capital Territory." },
  { name: "Jabi Lakeside Court",     region: "abuja", city: "Jabi, Abuja FCT",        x: 34.5, y: 42.9, status: "Now Selling",        type: "Mixed Use",   year: 2026, units: 186, desc: "Lakefront promenade living with boutique retail along the shores of Jabi Lake." },
  { name: "Bonny Waterfront",        region: "ph",    city: "Old GRA, Port Harcourt", x: 32.3, y: 78.4, status: "Under Construction", type: "Residential", year: 2027, units: 144, desc: "Garden-city residences on the Bonny River, designed around mangrove conservation parks." },
  { name: "Trans-Amadi Exchange",    region: "ph",    city: "Trans-Amadi, Port Harcourt", x: 33.8, y: 77.6, status: "Sold Out",        type: "Commercial",  year: 2023, units: 64,  desc: "The Niger Delta's premier business campus, anchoring the Trans-Amadi industrial corridor." },
  { name: "Dala Hill Residences",    region: "kano",  city: "Nassarawa GRA, Kano",    x: 42.9, y: 17.9, status: "Now Selling",        type: "Residential", year: 2026, units: 120, desc: "Courtyard homes inspired by ancient Kano architecture, shaded by centuries-old neem trees." },
  { name: "Kofar Trade Centre",      region: "kano",  city: "Bompai, Kano",           x: 44.4, y: 19.4, status: "Under Construction", type: "Commercial",  year: 2028, units: 72,  desc: "A modern trade hub honouring Kano's thousand-year legacy as the crossroads of Saharan commerce." },
  { name: "Ibadan Garden Estate",    region: "lagos", city: "Bodija, Ibadan",         x: 10.0, y: 57.2, status: "Now Selling",        type: "Residential", year: 2026, units: 198, desc: "Low-rise family living amid the rolling brown roofs and seven hills of Ibadan." },
];

const REGION_COUNTS = { lagos: 18, abuja: 12, ph: 9, kano: 6, all: 45 };
const REGION_FOCUS = {
  lagos: { x: 380, y: -130, scale: 1.6 },
  abuja: { x: 150, y: 60,   scale: 1.5 },
  ph:    { x: 190, y: -260, scale: 1.6 },
  kano:  { x: 70,  y: 270,  scale: 1.5 },
  all:   { x: 0,   y: 0,    scale: 1 },
};

const $ = (id) => document.getElementById(id);

// ---------- state ----------
let activeIndex = 0;
let activeRegion = "all";
let filters = { status: new Set(), type: new Set() };
let zoom = 1;
let panX = 0, panY = 0;

// ---------- preloader ----------
(function preload() {
  let pct = 0;
  const bar = $("preloaderBar"), label = $("preloaderPct");
  const tick = setInterval(() => {
    pct = Math.min(100, pct + Math.ceil(Math.random() * 14));
    bar.style.width = pct + "%";
    label.textContent = String(pct).padStart(2, "0");
    if (pct >= 100) {
      clearInterval(tick);
      setTimeout(() => $("preloader").classList.add("is-done"), 350);
    }
  }, 110);
})();

// ---------- markers ----------
const markersHost = $("markers");
PROJECTS.forEach((p, i) => {
  const el = document.createElement("button");
  el.className = "marker";
  el.style.left = p.x + "%";
  el.style.top = p.y + "%";
  el.setAttribute("aria-label", p.name);
  el.innerHTML = `<span class="marker-tip">${p.name}</span>`;
  el.addEventListener("click", (e) => { e.stopPropagation(); selectProject(i); });
  markersHost.appendChild(el);
  p.el = el;
});

// ---------- project card ----------
function visibleProjects() {
  return PROJECTS.filter((p) =>
    (activeRegion === "all" || p.region === activeRegion) &&
    (filters.status.size === 0 || filters.status.has(p.status)) &&
    (filters.type.size === 0 || filters.type.has(p.type))
  );
}

function selectProject(globalIndex) {
  activeIndex = globalIndex;
  const p = PROJECTS[globalIndex];
  const pool = visibleProjects();
  const card = $("projectCard");

  card.classList.remove("is-swapping");
  void card.offsetWidth; // restart animation
  card.classList.add("is-swapping");

  $("cardIndex").textContent = String(Math.max(1, pool.indexOf(p) + 1)).padStart(2, "0");
  $("cardTotal").textContent = String(pool.length || REGION_COUNTS[activeRegion]).padStart(2, "0");
  $("cardTitle").textContent = p.name;
  $("cardLoc").textContent = "HUB–DISTRICT · " + p.city.toUpperCase();
  $("cardType").textContent = p.type;
  $("cardYear").textContent = p.year;
  $("cardUnits").textContent = p.units;
  $("cardDesc").textContent = p.desc;

  const status = $("cardStatus");
  status.textContent = p.status;
  status.dataset.tone = p.status === "Sold Out" ? "sold" : p.status === "Under Construction" ? "construction" : "selling";

  PROJECTS.forEach((q) => q.el.classList.toggle("is-active", q === p));
  updateCoords(p);
}

function step(dir) {
  const pool = visibleProjects();
  if (!pool.length) return;
  const current = pool.indexOf(PROJECTS[activeIndex]);
  const next = pool[(current + dir + pool.length) % pool.length];
  selectProject(PROJECTS.indexOf(next));
  playTick();
}

$("prevBtn").addEventListener("click", () => step(-1));
$("nextBtn").addEventListener("click", () => step(1));
document.addEventListener("keydown", (e) => {
  if ($("detail").classList.contains("is-open")) return;
  if (e.key === "ArrowRight") step(1);
  if (e.key === "ArrowLeft") step(-1);
});

// ---------- regions ----------
document.querySelectorAll(".region").forEach((btn) => {
  btn.addEventListener("click", () => {
    activeRegion = btn.dataset.region;
    document.querySelectorAll(".region").forEach((b) => b.classList.toggle("is-active", b === btn));
    applyVisibility();
    const focus = REGION_FOCUS[activeRegion];
    zoom = focus.scale;
    panX = focus.x; panY = focus.y;
    applyTransform();
    const pool = visibleProjects();
    if (pool.length) selectProject(PROJECTS.indexOf(pool[0]));
    playTick();
  });
});

// ---------- filters ----------
$("filterBtn").addEventListener("click", () => {
  const open = $("filterPanel").classList.toggle("is-open");
  $("filterBtn").setAttribute("aria-expanded", open);
  $("filterPanel").setAttribute("aria-hidden", !open);
});

document.querySelectorAll("#statusFilters .filter-opt").forEach((b) =>
  b.addEventListener("click", () => toggleFilter(b, "status", b.dataset.status)));
document.querySelectorAll("#typeFilters .filter-opt").forEach((b) =>
  b.addEventListener("click", () => toggleFilter(b, "type", b.dataset.type)));

function toggleFilter(btn, kind, value) {
  const set = filters[kind];
  set.has(value) ? set.delete(value) : set.add(value);
  btn.classList.toggle("is-on", set.has(value));
  syncFilters();
}

$("filterClear").addEventListener("click", () => {
  filters.status.clear();
  filters.type.clear();
  document.querySelectorAll(".filter-opt").forEach((b) => b.classList.remove("is-on"));
  syncFilters();
});

function syncFilters() {
  const n = filters.status.size + filters.type.size;
  $("filterCount").textContent = "[" + String(n).padStart(2, "0") + "]";
  applyVisibility();
  const pool = visibleProjects();
  if (pool.length && !pool.includes(PROJECTS[activeIndex])) {
    selectProject(PROJECTS.indexOf(pool[0]));
  } else {
    selectProject(activeIndex);
  }
}

function applyVisibility() {
  const pool = visibleProjects();
  PROJECTS.forEach((p) => p.el.classList.toggle("is-dim", !pool.includes(p)));
}

// ---------- map pan & zoom ----------
const viewport = $("mapViewport");
const pane = $("mapPane");

function applyTransform() {
  pane.style.transform = `translate(${panX}px, ${panY}px) scale(${zoom})`;
  $("zoomLevel").textContent = zoom.toFixed(1) + "x";
  // compass needle drifts subtly with pan, like a held instrument
  const needle = document.querySelector(".compass-needle");
  if (needle) needle.style.transform = `rotate(${(panX * -0.05).toFixed(1)}deg)`;
}

function setZoom(z) {
  zoom = Math.min(3, Math.max(0.6, z));
  applyTransform();
}

$("zoomIn").addEventListener("click", () => { setZoom(zoom + 0.2); playTick(); });
$("zoomOut").addEventListener("click", () => { setZoom(zoom - 0.2); playTick(); });

viewport.addEventListener("wheel", (e) => {
  e.preventDefault();
  setZoom(zoom + (e.deltaY < 0 ? 0.12 : -0.12));
}, { passive: false });

let dragging = false, startX = 0, startY = 0, originX = 0, originY = 0;
viewport.addEventListener("pointerdown", (e) => {
  dragging = true;
  startX = e.clientX; startY = e.clientY;
  originX = panX; originY = panY;
  pane.classList.add("is-dragging");
  viewport.setPointerCapture(e.pointerId);
});
viewport.addEventListener("pointermove", (e) => {
  if (!dragging) return;
  panX = originX + (e.clientX - startX);
  panY = originY + (e.clientY - startY);
  applyTransform();
});
viewport.addEventListener("pointerup", () => { dragging = false; pane.classList.remove("is-dragging"); });
viewport.addEventListener("pointercancel", () => { dragging = false; pane.classList.remove("is-dragging"); });

// ---------- coordinates readout ----------
function updateCoords(p) {
  // invert the marker projection back to approximate lat/lon
  const lon = 2.5 + (p.x / 100) * 800 / 57;
  const lat = 14.2 - (p.y / 100) * 680 / 57;
  $("coords").innerHTML = `${lat.toFixed(4)}&deg; N &middot; ${lon.toFixed(4)}&deg; E`;
}

// ---------- sound ----------
let audioCtx = null, soundOn = false;
const soundToggle = $("soundToggle");

soundToggle.addEventListener("click", () => {
  soundOn = !soundOn;
  soundToggle.setAttribute("aria-pressed", soundOn);
  $("soundLabel").textContent = soundOn ? "Sound On" : "Sound Off";
  if (soundOn) playTick();
});

function playTick() {
  if (!soundOn) return;
  audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
  const osc = audioCtx.createOscillator();
  const gain = audioCtx.createGain();
  osc.type = "sine";
  osc.frequency.value = 660;
  gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
  gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.12);
  osc.connect(gain).connect(audioCtx.destination);
  osc.start();
  osc.stop(audioCtx.currentTime + 0.13);
}

// ---------- project detail overlay ----------
const detail = $("detail");

// scatter lit windows over the skyline silhouette
(function buildWindows() {
  const host = document.querySelector(".skyline-windows");
  const towers = [
    { x: 20, y: 120, w: 34, h: 140 },
    { x: 62, y: 70, w: 40, h: 190 },
    { x: 110, y: 100, w: 30, h: 160 },
    { x: 148, y: 140, w: 34, h: 120 },
  ];
  towers.forEach((t) => {
    for (let wy = t.y + 8; wy < t.y + t.h - 8; wy += 12) {
      for (let wx = t.x + 5; wx < t.x + t.w - 6; wx += 9) {
        if (Math.random() < 0.45) continue;
        const r = document.createElementNS("http://www.w3.org/2000/svg", "rect");
        r.setAttribute("x", wx); r.setAttribute("y", wy);
        r.setAttribute("width", 4); r.setAttribute("height", 5);
        r.setAttribute("opacity", (0.2 + Math.random() * 0.6).toFixed(2));
        host.appendChild(r);
      }
    }
  });
})();

function openDetail() {
  const p = PROJECTS[activeIndex];
  $("detailTitle").textContent = p.name;
  $("detailLoc").textContent = "HUB–DISTRICT · " + p.city.toUpperCase();
  $("detailDesc").textContent = p.desc;
  $("detailType").textContent = p.type;
  $("detailYear").textContent = p.year;
  $("detailUnits").textContent = p.units;
  const lon = 2.5 + (p.x / 100) * 800 / 57;
  const lat = 14.2 - (p.y / 100) * 680 / 57;
  $("detailCoords").textContent = `${lat.toFixed(2)}° N, ${lon.toFixed(2)}° E`;
  const status = $("detailStatus");
  status.textContent = p.status;
  status.dataset.tone = p.status === "Sold Out" ? "sold" : p.status === "Under Construction" ? "construction" : "selling";
  $("enquiryDone").hidden = true;
  $("enquiryForm").reset();
  detail.classList.add("is-open");
  detail.setAttribute("aria-hidden", "false");
  $("detailClose").focus();
  playTick();
}

function closeDetail() {
  detail.classList.remove("is-open");
  detail.setAttribute("aria-hidden", "true");
}

$("cardCta").addEventListener("click", (e) => { e.preventDefault(); openDetail(); });
$("detailClose").addEventListener("click", closeDetail);
detail.addEventListener("click", (e) => { if (e.target === detail) closeDetail(); });
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && detail.classList.contains("is-open")) closeDetail();
});

$("enquiryForm").addEventListener("submit", (e) => {
  e.preventDefault();
  $("enquiryDone").hidden = false;
  e.target.reset();
  playTick();
});

// ---------- menu ----------
$("menuBtn").addEventListener("click", () => {
  const open = $("megaMenu").classList.toggle("is-open");
  $("menuBtn").setAttribute("aria-expanded", open);
  $("megaMenu").setAttribute("aria-hidden", !open);
});
$("megaMenu").addEventListener("click", (e) => {
  if (e.target === e.currentTarget) $("menuBtn").click();
});
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && $("megaMenu").classList.contains("is-open")) $("menuBtn").click();
});

// ---------- init ----------
applyVisibility();
selectProject(0);
applyTransform();
