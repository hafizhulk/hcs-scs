// app.js — init + progressive enhancement (hanya browser asli).
// Berkas calculations/charts/ui/hcs/scs/compare/calib/cases dimuat sebelum berkas ini.
function syncRangeNumbers(){
  const ranges=document.querySelectorAll('input[type=range][id]');
  if(!ranges||typeof ranges.forEach!=='function')return;
  ranges.forEach(function(r){
    const n=document.getElementById(r.id+'-num');
    if(!n)return;
    n.min=r.min;n.max=r.max;if(r.step)n.step=r.step;n.value=r.value;
  });
}

function enhanceRangeInputs(){
  try{
    const probe=document.createElement('div');
    if(!probe||typeof probe.appendChild!=='function')return;
    const ranges=document.querySelectorAll('input[type=range][id]');
    if(!ranges||typeof ranges.forEach!=='function')return;
    ranges.forEach(function(r){
      if(!r.parentNode||typeof r.insertAdjacentElement!=='function')return;
      if(document.getElementById(r.id+'-num'))return;
      const n=document.createElement('input');
      n.type='number';n.id=r.id+'-num';n.className='range-num';
      n.min=r.min;n.max=r.max;if(r.step)n.step=r.step;n.value=r.value;
      n.setAttribute('aria-label','Nilai '+r.id);
      r.insertAdjacentElement('afterend',n);
      r.addEventListener('input',function(){n.value=r.value;});
      n.addEventListener('input',function(){
        if(n.value===''||!Number.isFinite(Number(n.value)))return;
        r.value=n.value;
        const evt=typeof Event==='function'?new Event('input',{bubbles:true}):document.createEvent('Event');
        if(evt.initEvent)evt.initEvent('input',true,true);
        r.dispatchEvent(evt);
      });
    });
  }catch(e){/* enhancement opsional */}
}

uHCS();uSCS();uCmp();doReg();kHCS();renderSCSCase();
enhanceRangeInputs();syncRangeNumbers();
if(typeof Chart!=='function'){
  sv('chart-status','Grafik belum tersedia karena pustaka grafik belum termuat. Perhitungan angka tetap dapat digunakan.');
  document.getElementById('chart-status').hidden=false;
}
