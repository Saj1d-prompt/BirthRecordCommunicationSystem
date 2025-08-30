// ==========================
// Utilities
// ==========================
const rand = (min, max) => Math.random() * (max - min) + min;
const randint = (min, max) => Math.floor(rand(min, max + 1));
const clamp = (x, a, b) => Math.max(a, Math.min(b, x));
const parseDate = (s) => new Date(s + "T00:00:00");
const monthKey = (d) => `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}`;
const within = (dt, from, to) => (!from || dt >= from) && (!to || dt <= to);
const by = (arr, keyFn) => arr.reduce((m, x) => (m[keyFn(x)] ??= [], m[keyFn(x)].push(x), m), {});

// ==========================
// Dummy dataset (lots of rows)
// ==========================
(function buildDummy(){
  const start = new Date("2025-06-01");
  const end   = new Date("2025-08-31");
  const locs = ["Dhaka","Chittagong","Barisal","Khulna","Rajshahi","Sylhet","Rangpur","Mymensingh"];
  const genders = ["Male","Female","Other"];
  let reg = 100000;
  const newborn_t = [];
  for (let d = new Date(start); d <= end; d.setDate(d.getDate()+1)) {
    const births = randint(30, 60);
    for (let i=0;i<births;i++){
      reg++;
      newborn_t.push({
        birthRegistrationNum: reg,
        weight: Number(clamp(rand(2,4.5)+rand(-.2,.2),1.5,5).toFixed(2)),
        gender: genders[randint(0,2)],
        location: locs[randint(0,locs.length-1)],
        dateofBirth: d.toISOString().slice(0,10),
        gestation: randint(34,42)
      });
    }
  }
  window.DUMMY_DB = { newborn_t };
})();

// ==========================
// Top Menu toggle (mobile)
// ==========================
function initMenu(){
  const btn = document.getElementById('menuToggle');
  const nav = document.getElementById('brcMenu');
  if (!btn || !nav) return;
  btn.addEventListener('click', () => nav.classList.toggle('open'));
}

// ==========================
// Chart states
// ==========================
let CHARTS = { f1:null,f2:null,f3:null,f4:null,f5:null, fig6Bar:null };

// ==========================
// Dynamic controls
// ==========================
function populateLocationFilter(){
  const sel = document.getElementById('f1_loc');
  if (!sel) return;
  const uniq = Array.from(new Set(window.DUMMY_DB.newborn_t.map(r => r.location))).sort();
  sel.innerHTML = `<option value="">All locations</option>` + uniq.map(loc => `<option>${loc}</option>`).join('');
}

// ==========================
// Figure 1: Stacked bar (monthly x gender)
// ==========================
function renderFig1(){
  const rows = DUMMY_DB.newborn_t;
  const from = f1_from.value ? new Date(f1_from.value) : null;
  const to   = f1_to.value   ? new Date(f1_to.value)   : null;
  const loc  = f1_loc.value;
  const filtered = rows.filter(r => within(parseDate(r.dateofBirth),from,to) && (!loc || r.location===loc));
  const byM = by(filtered, r=>monthKey(parseDate(r.dateofBirth)));
  const months = Object.keys(byM).sort();
  const genders = ['Male','Female','Other'];
  const stacks = genders.map(g=>months.map(m=>byM[m].filter(x=>x.gender===g).length));
  if(CHARTS.f1) CHARTS.f1.destroy();
  CHARTS.f1 = new Chart(document.getElementById('stackedBarChart'), {
    type:'bar',
    data:{ labels: months, datasets: genders.map((g,i)=>({label:g, data: stacks[i]})) },
    options:{ responsive:true, maintainAspectRatio:false, scales:{ x:{stacked:true}, y:{stacked:true, beginAtZero:true } } }
  });
}

// ==========================
// Figure 2: Box plot (min/Q1/med/Q3/max)
// ==========================
function renderFig2(){
  const rows = DUMMY_DB.newborn_t;
  const from = f2_from.value ? new Date(f2_from.value) : null;
  const to   = f2_to.value   ? new Date(f2_to.value)   : null;
  const g    = f2_gender.value;
  const filtered = rows.filter(r=>within(parseDate(r.dateofBirth),from,to) && (!g || r.gender===g));
  const vals = filtered.map(r=>r.weight).sort((a,b)=>a-b);
  const percentile = (p) => {
    if (vals.length === 0) return 0;
    const idx = (p/100)*(vals.length-1);
    const lo = Math.floor(idx), hi = Math.ceil(idx), t = idx - lo;
    return vals[lo] + (vals[hi] - vals[lo]) * t;
  };
  const data = vals.length ? [vals[0],percentile(25),percentile(50),percentile(75),vals[vals.length-1]] : [0,0,0,0,0];
  if(CHARTS.f2) CHARTS.f2.destroy();
  CHARTS.f2 = new Chart(document.getElementById('weightBoxChart'), {
    type:'bar',
    data:{ labels:['Min','Q1','Median','Q3','Max'], datasets:[{ label:'kg', data, backgroundColor:'#4fa6d7' }] },
    options:{ responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } } }
  });
}

// ==========================
// Heatmap helpers
// ==========================
function ensureCanvasSize(id, h=360){
  const c = document.getElementById(id);
  if (!c) return null;
  if (!c.clientHeight) c.style.height = h + "px";
  c.width  = c.clientWidth  || 600;
  c.height = c.clientHeight || h;
  return c;
}
function heatColor(v, vmax){
  if (vmax <= 0) return 'rgb(255,255,255)';
  const t = v / vmax;
  const R = Math.floor(255 * t);
  const G = Math.floor(255 * (1 - Math.abs(t - 0.5) * 2));
  const B = Math.floor(255 * (1 - t));
  return `rgb(${R},${G},${B})`;
}
function drawHeatmapGrid(canvasId, matrix, yLabels){
  const c = ensureCanvasSize(canvasId, 360);
  if (!c) return;
  const ctx = c.getContext('2d');
  ctx.clearRect(0,0,c.width,c.height);
  const rows = matrix.length, cols = matrix[0].length;

  const padL=64, padT=36, padB=44, padR=24;
  const w = c.width - padL - padR, h = c.height - padT - padB;
  const cellW = w / cols, cellH = h / rows;
  let vmax=0; matrix.forEach(r=>r.forEach(v=>{ if(v>vmax)vmax=v; }));

  ctx.font='12px Segoe UI'; ctx.textAlign='right'; ctx.textBaseline='middle'; ctx.fillStyle='#374151';
  yLabels.forEach((lbl,r)=>ctx.fillText(lbl, padL - 10, padT + r*cellH + cellH/2));

  ctx.textAlign='center'; ctx.textBaseline='alphabetic';
  for(let col=0; col<cols; col+=3){
    ctx.fillText(String(col).padStart(2,'0'), padL + col*cellW + cellW/2, c.height - 8);
  }

  for(let r=0;r<rows;r++){
    for(let col=0;col<cols;col++){
      ctx.fillStyle=heatColor(matrix[r][col],vmax);
      ctx.fillRect(padL+col*cellW, padT+r*cellH, Math.ceil(cellW), Math.ceil(cellH));
    }
  }

  ctx.strokeStyle='rgba(0,0,0,0.08)'; ctx.lineWidth=1;
  for(let r=0;r<=rows;r++){const y=padT+r*cellH+0.5;ctx.beginPath();ctx.moveTo(padL,y);ctx.lineTo(padL+w,y);ctx.stroke();}
  for(let col=0;col<=cols;col++){const x=padL+col*cellW+0.5;ctx.beginPath();ctx.moveTo(x,padT);ctx.lineTo(x,padT+h);ctx.stroke();}

  const gap = 12;
  const lgX = padL + gap, lgY = 10, lgW = 180, lgH = 12;
  for(let i=0;i<lgW;i++){
    ctx.fillStyle = heatColor(i/(lgW-1)*vmax, vmax);
    ctx.fillRect(lgX + i, lgY, 1, lgH);
  }
  ctx.fillStyle = '#374151';
  ctx.font = '12px Segoe UI';
  ctx.textAlign = 'right';
  ctx.fillText('Low', lgX - 8, lgY + lgH/2 + 4);
  ctx.textAlign = 'left';
  ctx.fillText('High', lgX + lgW + 8, lgY + lgH/2 + 4);
}

// ==========================
// Figure 3: Heatmap
// ==========================
function renderFig3(){
  const rows = DUMMY_DB.newborn_t;
  const from = f3_from.value ? new Date(f3_from.value) : null;
  const to   = f3_to.value   ? new Date(f3_to.value)   : null;
  const M = Array.from({length:7},()=>Array(24).fill(0));
  for(const r of rows){
    const d=parseDate(r.dateofBirth);
    if(!within(d,from,to)) continue;
    const weekday=d.getDay();
    const hour=(d.getDate()+(r.birthRegistrationNum%24))%24;
    M[weekday][hour]++;
    if(Math.random()<0.3) M[weekday][(hour+23)%24]++;
    if(Math.random()<0.3) M[weekday][(hour+1)%24]++;
  }
  drawHeatmapGrid('heatmapChart', M, ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']);
}

// ==========================
// Figure 4: Scatter
// ==========================
const F4_STATE = { gender:'', from:null, to:null };
function f4ApplyPreset(key){
  const rows = DUMMY_DB.newborn_t;
  if (key === 'all'){ F4_STATE.from=null; F4_STATE.to=null; renderFig4(); return; }
  const dates = rows.map(r=>r.dateofBirth).sort();
  const end = new Date(dates[dates.length-1]+"T00:00:00");
  let start=new Date(end);
  if(key==='last7d') start=new Date(end.getTime()-6*86400000);
  if(key==='last30d') start=new Date(end.getTime()-29*86400000);
  F4_STATE.from=start;F4_STATE.to=end; renderFig4();
}
function wireFig4Creative(){
  document.querySelectorAll('[data-f4-preset]').forEach(btn=>btn.addEventListener('click',()=>f4ApplyPreset(btn.getAttribute('data-f4-preset'))));
  document.querySelectorAll('[data-f4-gender]').forEach(btn=>btn.addEventListener('click',()=>{F4_STATE.gender=btn.getAttribute('data-f4-gender');renderFig4();}));
}
function renderFig4(){
  let rows=DUMMY_DB.newborn_t;
  if(F4_STATE.gender) rows=rows.filter(r=>r.gender===F4_STATE.gender);
  rows=rows.filter(r=>within(parseDate(r.dateofBirth),F4_STATE.from,F4_STATE.to));
  const sample=rows.length>8000?rows.filter((_,i)=>i%2===0):rows;
  const data=sample.map(r=>({x:r.gestation,y:r.weight}));
  if(CHARTS.f4)CHARTS.f4.destroy();
  CHARTS.f4=new Chart(document.getElementById('scatterChart'),{
    type:'scatter',
    data:{datasets:[{label:`n=${data.length}`,data}]},
    options:{responsive:true,maintainAspectRatio:false,scales:{x:{min:34,max:42,title:{display:true,text:'Gestation (weeks)'}},y:{min:1.5,max:5,title:{display:true,text:'Weight (kg)'}}}}
  });
}

// ==========================
// Figure 5: Pie
// ==========================
function renderFig5(){
  const rows=DUMMY_DB.newborn_t;
  const from=f5_from.value?new Date(f5_from.value):null;
  const to=f5_to.value?new Date(f5_to.value):null;
  const filtered=rows.filter(r=>within(parseDate(r.dateofBirth),from,to));
  const genders=['Male','Female','Other'];
  const counts=genders.map(g=>filtered.filter(r=>r.gender===g).length);
  if(CHARTS.f5)CHARTS.f5.destroy();
  CHARTS.f5=new Chart(document.getElementById('genderPieChart'),{
    type:'pie',
    data:{labels:genders,datasets:[{data:counts}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}}}
  });
}

// ==========================
// Figure 6: Location insights
// ==========================
function renderFig6(){
  const rows=DUMMY_DB.newborn_t;
  const from=f5_from.value?new Date(f5_from.value):null;
  const to=f5_to.value?new Date(f5_to.value):null;
  const filtered=rows.filter(r=>within(parseDate(r.dateofBirth),from,to));

  const total=filtered.length;
  const male   = filtered.filter(r=>r.gender==='Male').length;
  const female = filtered.filter(r=>r.gender==='Female').length;
  const other  = filtered.filter(r=>r.gender==='Other').length;
  const pct = (n)=> total ? (n/total*100).toFixed(1)+'%' : '0.0%';
  document.getElementById('fig6_total').textContent = total;
  document.getElementById('fig6_male').textContent  = pct(male);
  document.getElementById('fig6_female').textContent= pct(female);
  document.getElementById('fig6_other').textContent = pct(other);

  const byLoc = by(filtered, r=>r.location);
  const top = Object.entries(byLoc).map(([k,v])=>[k,v.length]).sort((a,b)=>b[1]-a[1]).slice(0,5);
  const labels = top.map(([k])=>k);
  const values = top.map(([,v])=>v);
  if (CHARTS.fig6Bar) CHARTS.fig6Bar.destroy();
  CHARTS.fig6Bar = new Chart(document.getElementById('fig6_bar'), {
    type:'bar',
    data:{ labels, datasets:[{ data: values }] },
    options:{ responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } }, plugins:{ legend:{ display:false } } }
  });
}

// ==========================
// Table
// ==========================
function renderTableAll(){
  const rows=DUMMY_DB.newborn_t;
  const tb=document.querySelector('#advancedTable tbody');
  tb.innerHTML=rows.slice(0,10000).map(r=>`<tr><td>${r.dateofBirth}</td><td>${r.gender}</td><td>${r.weight}</td><td>${r.gestation}</td><td>${r.location}</td></tr>`).join('');
  if($.fn.DataTable.isDataTable('#advancedTable'))$('#advancedTable').DataTable().destroy();
  $('#advancedTable').DataTable();
}

// ==========================
// Bind apply/reset
// ==========================
function bindFigure(formId, renderFn, alsoRun=[]){
  const form=document.getElementById(formId); if(!form)return;
  form.querySelectorAll('[data-action="apply"]').forEach(btn=>btn.addEventListener('click',()=>{ renderFn(); alsoRun.forEach(fn=>fn()); }));
  form.querySelectorAll('[data-action="reset"]').forEach(btn=>btn.addEventListener('click',()=>{ form.querySelectorAll('input,select').forEach(el=>el.value=''); renderFn(); alsoRun.forEach(fn=>fn()); }));
}

// ==========================
// Init
// ==========================
window.addEventListener('load',()=>{
  initMenu();                    // header toggle
  populateLocationFilter();

  renderFig1();renderFig2();renderFig3();renderFig4();renderFig5();renderFig6();renderTableAll();

  bindFigure('f1',renderFig1);
  bindFigure('f2',renderFig2);
  bindFigure('f3',renderFig3);
  bindFigure('f5',renderFig5,[renderFig6]);

  wireFig4Creative();

  window.addEventListener('resize',()=>{renderFig1();renderFig2();renderFig3();renderFig4();renderFig5();renderFig6();});
});
