function kHCS(){
  try{
    const p=readFields({pc:'k_pc',uc:'k_uc',dbc:'k_dbc',s:'k_s',x:'k_x',a:'k_a',b:'k_b',H:'k_H',w:'k_w',t1:'k_t1',t2:'k_t2',vd:'k_vd',c:'k_c',f:'k_f'}),v=calcHCS(p);
    sv('k-out',`P_HCS = ${p.pc} + ${p.uc} + ${p.dbc} = ${v.P.toFixed(4)} jam/rit
h = ${p.a} + ${p.b} × ${p.x} = ${v.h.toFixed(4)} jam/rit
T_HCS = ${v.P.toFixed(4)} + ${p.s} + ${v.h.toFixed(4)} = ${v.T.toFixed(4)} jam/rit

Waktu operasi tersedia = max(0, ${p.H}×(1−${p.w}) − (${p.t1}+${p.t2})) = ${v.available.toFixed(4)} jam
N_kapasitas = floor(${v.available.toFixed(4)} / ${v.T.toFixed(4)}) = ${v.capacity} rit/hari
N_kebutuhan = ceil(${p.vd} / (${p.c}×${p.f})) = ${v.required} rit/hari
Ritasi satu kendaraan = ${v.done} rit/hari
Volume terangkut = ${fmt(p.vd-v.remaining)} m³/hari
Volume belum terangkut = ${fmt(v.remaining)} m³/hari
Armada minimum = ${v.fleet===null?'Tidak dapat dipenuhi pada jam kerja ini':v.fleet+' kendaraan identik'}
Waktu untuk seluruh sampah (satu kendaraan) = ${v.Hneeded.toFixed(2)} jam

${planMessage(v)}`);
  }catch(error){sv('k-out',error.message);}
}

function renderSCSCase(){
  const p={V:10,r:2.5,c:.5,f:.9,uc:.05,np:8,dbc:.02,s:.1,x:25,a:.05,b:.025,vd:120,w:.15,t1:.15,t2:.15},v=calcSCS(p);
  const steps=[
    ['Kapasitas kontainer utuh',`C_T = floor((10×2,5)/(0,5×0,9)) = floor(${fmt(v.CTraw)}) = ${v.CT} kontainer/rit. Muatan efektif ${fmt(v.q)} m³ lepas/rit.`],
    ['Kebutuhan kontainer dan ritasi',`C_hari = ceil(120/(0,5×0,9)) = ${v.containers} kontainer. Nd = ceil(${v.containers}/${v.CT}) = ${v.Nd} rit/hari: ${v.fullTrips} rit penuh dan 1 rit ${v.lastContainers} kontainer.`],
    ['Pickup ritasi penuh',`P = ${v.CT}×0,05 + (8−1)×0,02 = ${fmt(v.P)} jam/rit.`],
    ['Pickup ritasi terakhir',`Lokasi terakhir ≈ ceil(${v.lastContainers}×8/${v.CT}) = ${v.lastLocations}. P_terakhir = ${v.lastContainers}×0,05 + (${v.lastLocations}−1)×0,02 = ${fmt(v.lastP)} jam.`],
    ['Haul dan waktu ritasi penuh',`h = 0,050 + 0,025×25 = ${fmt(v.h)} jam. T_penuh = ${fmt(v.P)} + 0,10 + ${fmt(v.h)} = ${fmt(v.T)} jam/rit.`],
    ['Waktu kerja harian',`H = [(0,15+0,15) + ${v.fullTrips}×${fmt(v.T)} + (${fmt(v.lastP)}+0,10+${fmt(v.h)})]/0,85 = ${v.H.toFixed(2)} jam/hari. Melebihi 8 jam satu kendaraan; evaluasi pembagian rute dan armada.`]
  ];
  document.getElementById('case-scs-out').innerHTML=steps.map(([title,body],i)=>`<div class="step-item"><div class="step-num so">${i+1}</div><div class="step-content"><strong>${title}</strong><br>${body}</div></div>`).join('')+'<p>Estimasi lokasi terakhir memakai sebaran kontainer merata. Haul setiap ritasi mencakup kembali ke area pengumpulan; t₂ dihitung dari area itu ke garasi.</p>';
}
