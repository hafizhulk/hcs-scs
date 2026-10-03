// Run with Node.js: node tests/calculations.test.js
// No packages required. Includes DOM/range fixtures, not a browser renderer.
const JS_ORDER=['calculations','charts','ui','hcs','scs','compare','calib','cases'];
function loadSource(dir){
  const fs=require('node:fs'),path=require('node:path');
  return JS_ORDER.map(n=>fs.readFileSync(path.join(dir,'..','js',n+'.js'),'utf8')).join('\n');
}
function runTests(html, source){
  const markup=html.replace(/<script\b[^>]*>[\s\S]*?<\/script>/g,'');
  const results=[];
  function assert(condition,message){if(!condition)throw new Error(message);}
  function near(actual,expected,tolerance=1e-10){assert(Math.abs(actual-expected)<=tolerance*Math.max(1,Math.abs(expected)),`${actual} != ${expected}`);}
  function throws(fn){let rejected=false;try{fn();}catch{rejected=true;}assert(rejected,'Expected rejection');}
  function test(name,fn){try{fn();results.push({name,passed:true});}catch(error){results.push({name,passed:false,error:error.message});}}
  function attrs(tag){return Object.fromEntries([...tag.matchAll(/([\w-]+)="([^"]*)"/g)].map(m=>[m[1],m[2]]));}
  function createApp(withChart=true){
    const elements=new Map(),inputs=[],classes=new Map(),charts=[];let rows=[];
    function element(attributes={}){
      const node={style:{},hidden:false,classList:{add(){},remove(){}},getContext(){return {id:node.id};}};
      let text='',inner='',value=attributes.value||'',min=attributes.min||'0',max=attributes.max||'100',step=attributes.step||'1';
      function sanitize(v){
        if(attributes.type!=='range')return String(v);
        let n=Number(v),lo=Number(min),hi=Number(max);if(!Number.isFinite(n))n=(lo+hi)/2;
        n=Math.max(lo,Math.min(hi,n));
        if(step!=='any'){n=lo+Math.round((n-lo)/Number(step))*Number(step);n=Math.max(lo,Math.min(hi,n));}
        return String(n);
      }
      Object.assign(node,attributes);
      Object.defineProperties(node,{
        value:{get:()=>value,set:v=>{value=sanitize(v);}},
        min:{get:()=>min,set:v=>{min=String(v);value=sanitize(value);}},
        max:{get:()=>max,set:v=>{max=String(v);value=sanitize(value);}},
        step:{get:()=>step,set:v=>{step=String(v);value=sanitize(value);}},
        textContent:{get:()=>text,set:v=>{text=String(v);inner='';}},
        innerHTML:{get:()=>inner,set:v=>{inner=String(v);text='';if(node.isRow)node.fields=[...inner.matchAll(/<input\b[^>]*>/g)].map(m=>element(attrs(m[0])));}}
      });
      value=sanitize(value);return node;
    }
    for(const match of markup.matchAll(/<\w+\b[^>]*>/g)){
      const a=attrs(match[0]);
      if(!a.id&&!a.class)continue;
      const node=element(a);if(a.id)elements.set(a.id,node);
      if(match[0].startsWith('<input'))inputs.push(node);
      for(const cls of (a.class||'').split(' ').filter(Boolean)){if(!classes.has(cls))classes.set(cls,[]);classes.get(cls).push(node);}
    }
    const xs=classes.get('cx2'),hs=classes.get('ch2');
    rows=xs.map((x,i)=>({querySelector:selector=>selector==='.cx2'?x:hs[i]}));
    const body=elements.get('cal-body');body.querySelectorAll=()=>rows;
    body.appendChild=row=>{row.querySelector=selector=>row.fields.find(f=>(f.class||'').split(' ').includes(selector.slice(1)));rows.push(row);};
    const document={
      getElementById:id=>elements.get(id)||null,
      querySelectorAll:selector=>selector==='#cal-body tr'?rows:classes.get(selector.slice(1))||[],
      createElement:()=>{const row=element();row.isRow=true;return row;}
    };
    class Chart{constructor(ctx,config){this.config=config;this.id=ctx.id;charts.push(this);}destroy(){this.destroyed=true;}}
    const api=new Function('document','Chart','window',source+'\nreturn {calcHCS,calcSCS,fitHaul,calcComparison,ceilCount,floorCount,uHCS,uSCS,uCmp,doReg,kHCS,renderSCSCase,toggleHCSUnit,loadSoalLatihan,addCalRow};')(document,withChart?Chart:undefined,{scrollTo(){}});
    api.uHCS();api.uSCS();api.uCmp();api.doReg();api.kHCS();api.renderSCSCase();
    if(!withChart){
      const st=elements.get('chart-status');
      if(st){st.textContent='Grafik belum tersedia karena pustaka grafik belum termuat. Perhitungan angka tetap dapat digunakan.';st.hidden=false;}
    }
    return {api,elements,charts,get:id=>elements.get(id).textContent,set:(id,value)=>elements.get(id).value=String(value),rows:()=>rows};
  }
  const app=createApp(),api=app.api;
  const hcs={pc:.067,uc:0,dbc:0,s:.053,x:16,a:.05,b:.025,H:8,w:.15,t1:.1,t2:.1,vd:45,c:6,f:.9};
  const scs={V:10,r:2.5,c:.5,f:.9,uc:.05,np:8,dbc:.02,s:.1,x:25,a:.05,b:.025,vd:120,w:.15,t1:.15,t2:.15};
  const cmp={x:20,a:.016,b:.011,ph:.067,sh:.053,ps:2.89,ss:.1,qh:5.4,qs:24.75,vd:120,H:8,w:.15,t1:.15,t2:.15};
  test('All calculators initialize with real output elements',()=>{
    near(Number(app.get('rHt')),.356);assert(app.get('rSct')==='55','SCS capacity');assert(app.get('rSnd')==='5','SCS demand');near(Number(app.get('rSH')),21.42,.0001);
    assert(app.get('ra')!=='—','Regression initialized');assert(app.get('cr-hcs-unit')!=='—','Comparison initialized');
  });
  test('HCS worked example preserves exact minutes',()=>{
    const v=api.calcHCS({...hcs,pc:.4,dbc:.1,s:.133,a:.016,b:.018,x:31,t1:15/60,t2:20/60});
    near(v.T,1.207);assert(v.capacity===5,'Five full trips');near((15/60+20/60+5*v.T)/.85,7.7862745098039206);
  });
  test('HCS shortage reports unmet demand and three vehicles',()=>{
    const v=api.calcHCS({...hcs,x:80});assert(v.capacity===3&&v.required===9&&v.fleet===3,'Capacity/demand/fleet');near(v.remaining,28.8);
    const ui=createApp();ui.set('k_x',80);ui.api.kHCS();assert(ui.get('k-out').includes('Volume belum terangkut = 28.8'),'Shortage visible');
  });
  test('HCS no-trip day and no-waste day',()=>{
    const noTime=api.calcHCS({...hcs,t1:8});assert(noTime.capacity===0&&noTime.fleet===null,'No negative capacity or infinite fleet');
    const empty=api.calcHCS({...hcs,vd:0});assert(empty.required===0&&empty.done===0&&empty.fleet===0&&empty.Hneeded===0,'No trips or dispatch when empty');
  });
  test('HCS rejects invalid or blank inputs and clears UI results',()=>{
    for(const p of [{c:0},{f:0},{f:1.1},{w:1},{w:-.1},{x:-1},{H:0},{pc:NaN},{vd:Infinity}])throws(()=>api.calcHCS({...hcs,...p}));
    const ui=createApp();ui.set('k_c',0);ui.api.kHCS();assert(!/Infinity|NaN/.test(ui.get('k-out')),'No invalid numerical output');
    ui.set('hp',''); // Real range inputs normalize blanks, as represented by this fixture.
    ui.set('k_pc','');ui.api.kHCS();assert(ui.get('k-out').includes('angka yang valid'),'Blank number rejected');
  });
  test('SCS corrected case includes a partial final trip',()=>{
    const v=api.calcSCS(scs);assert(v.CT===55&&v.containers===267&&v.Nd===5,'Correct discrete counts');
    assert(v.fullTrips===4&&v.lastContainers===47&&v.lastLocations===7,'Partial route');near(v.q,24.75);near(v.P,2.89);near(v.lastP,2.47);near(v.H,21.41764705882353);
    assert(app.elements.get('case-scs-out').innerHTML.includes('21.42'),'Case uses same result');
  });
  test('SCS compaction increases capacity and reduces hauling trips',()=>{
    const low=api.calcSCS({...scs,r:1}),high=api.calcSCS({...scs,r:4});
    assert(low.CT===22&&high.CT===88,'Compaction applied in numerator');assert(high.Nd<low.Nd,'Fewer disposal trips');
  });
  test('SCS rounding conserves whole containers at 250 m³',()=>{
    const v=api.calcSCS({...scs,vd:250});assert(v.Nd===11,'Ten nominal-capacity trips are insufficient');assert(v.containers===556,'Container count');
    near(v.fullTrips*v.CT+v.lastContainers,v.containers);assert(v.Nd*v.q>=250,'All volume serviced');
  });
  test('SCS exact full trips and zero demand',()=>{
    const v=api.calcSCS({...scs,vd:49.5});assert(v.Nd===2&&v.fullTrips===2&&v.lastContainers===0,'No extra last trip');near(v.H,(.3+2*3.665)/.85);
    const empty=api.calcSCS({...scs,vd:0});assert(empty.Nd===0&&empty.H===0&&empty.containers===0,'Empty-day handling');
  });
  test('SCS rejects impossible stops, zero-container capacity, and invalid factors',()=>{
    for(const p of [{np:56},{np:1.5},{np:0},{V:.1},{r:.9},{f:0},{f:1.1},{w:1},{c:0},{vd:-1}])throws(()=>api.calcSCS({...scs,...p}));
    const ui=createApp();ui.set('sv',2);ui.set('sc',4);ui.set('sr',1);ui.set('sf',1);ui.api.uSCS();assert(ui.get('rSct')==='—'&&ui.get('scs-status').includes('kontainer utuh'),'Invalid results cleared');
  });
  test('Floating-point boundaries do not invent trips or erase tiny demand',()=>{
    assert(api.ceilCount(.1+.2)===1,'Positive fractional count rounds upward');assert(api.ceilCount(.3/.1)===3,'Exact boundary');
    assert(api.floorCount(.3/.1)===3,'Exact downward boundary');assert(api.ceilCount(1e-20)===1,'Tiny positive demand');
  });
  test('Unit toggle preserves haul, coefficients, limits and custom scenarios',()=>{
    const ui=createApp();ui.set('hx',37.123);ui.set('hb',.026543);
    ui.api.uHCS();const initial=Number(ui.get('rHh')),initialX=Number(ui.elements.get('hx').value),initialB=Number(ui.elements.get('hb').value);
    ui.api.toggleHCSUnit();near(Number(ui.elements.get('hx').value),initialX/1.609344);near(Number(ui.elements.get('hb').value),initialB*1.609344);near(Number(ui.get('rHh')),initial,.0001);
    for(let i=0;i<19;i++)ui.api.toggleHCSUnit();near(Number(ui.elements.get('hx').value),initialX);near(Number(ui.elements.get('hb').value),initialB);near(Number(ui.elements.get('hx').max),80);
    ui.set('hx',1);ui.set('hb',.005);ui.api.toggleHCSUnit();near(Number(ui.elements.get('hx').value),1/1.609344);near(Number(ui.elements.get('hb').value),.005*1.609344);
  });
  test('Exercise loader preserves volume assumptions and exact 20 minutes',()=>{
    const ui=createApp();const volume=ui.elements.get('hvd').value;ui.api.loadSoalLatihan();assert(ui.elements.get('hvd').value===volume,'No invented volume');
    near(Number(ui.elements.get('ht2').value),20/60);assert(ui.get('rHnd1').endsWith('→ 5'),'Worked-example capacity');near(Number(ui.get('rHt')),1.207);
  });
  test('OLS recovers known coefficients with a missing middle row',()=>{
    const ui=createApp();const samples=[[10,.3],['',.5],[30,.7],[40,.9],['','']];
    ui.rows().forEach((row,i)=>{row.querySelector('.cx2').value=String(samples[i][0]);row.querySelector('.ch2').value=String(samples[i][1]);});ui.api.doReg();
    near(Number.parseFloat(ui.get('ra')),.1);near(Number.parseFloat(ui.get('rb')),.02);assert(ui.get('rinterp').includes('1 baris belum lengkap'),'Missing pair explained');
    const chart=ui.charts.filter(c=>c.id==='cal-ch'&&!c.destroyed).at(-1);assert(chart.config.data.datasets[0].data.length===3,'Only complete pairs graphed');
  });
  test('OLS rejects insufficient, duplicate-x and invalid measurements',()=>{
    throws(()=>api.fitHaul([{x:10,h:.3},{x:30,h:.7}]));throws(()=>api.fitHaul([{x:10,h:.3},{x:10,h:.5},{x:10,h:.7}]));
    throws(()=>api.fitHaul([{x:-1,h:.3},{x:10,h:.5},{x:30,h:.7}]));throws(()=>api.fitHaul([{x:1,h:Infinity},{x:10,h:.5},{x:30,h:.7}]));
    const ui=createApp();ui.rows().forEach(row=>row.querySelector('.cx2').value='');ui.api.doReg();assert(ui.get('ra')==='—'&&ui.get('rr2')==='—','Old fit cleared');
  });
  test('OLS constant time and negative slope receive physical warnings',()=>{
    const constant=api.fitHaul([{x:10,h:.3},{x:20,h:.3},{x:30,h:.3}]);assert(constant.b===0&&constant.r2===null,'Undefined R² handled');
    const decreasing=api.fitHaul([{x:10,h:.7},{x:20,h:.5},{x:30,h:.3}]);near(decreasing.b,-.02);assert(decreasing.warnings.length>0,'Negative slope warned despite high R²');
    assert(!app.get('rinterp').includes('valid untuk desain'),'Fit is not design validation');
  });
  test('OLS centered calculation remains stable for large distances',()=>{
    const v=api.fitHaul([{x:1e8,h:.3},{x:1e8+1,h:.5},{x:1e8+2,h:.7}]);near(v.b,.2);near(v.r2,1);
  });
  test('Adding an empty survey row preserves existing fit',()=>{
    const ui=createApp(),before=ui.get('rb');ui.api.addCalRow();assert(ui.rows().length===6&&ui.get('rb')===before,'Empty row does not shift data');
  });
  test('Comparison uses volume productivity, supports ties and distance crossover',()=>{
    assert(api.calcComparison(cmp).winner==='HCS','Short-haul default');assert(api.calcComparison({...cmp,x:80}).winner==='SCS','Long-haul crossover');
    const equal=api.calcComparison({...cmp,ph:.1,sh:.1,ps:.1,ss:.1,qh:10,qs:10});assert(equal.winner===null,'Explicit tie');
    const morePerTrip=api.calcComparison({...cmp,ph:.1,sh:.1,ps:1,ss:1,qh:1,qs:10});assert(morePerTrip.scs.T>morePerTrip.hcs.T&&morePerTrip.winner==='SCS','Longer trip may be more productive');
    throws(()=>api.calcComparison({...cmp,qs:0}));
  });
  test('Charts use numeric x axes and calculations survive missing Chart.js',()=>{
    for(const id of ['hcs-ch','scs-ch','cmp-line','cal-ch']){
      const chart=app.charts.find(c=>c.id===id);assert(chart.config.options.scales.x.type==='linear',id+' numeric scale');
    }
    const ui=createApp(false);assert(ui.get('rSct')==='55'&&ui.get('rHnd2')==='9','Numerical calculations work offline');assert(!ui.elements.get('chart-status').hidden,'Chart status shown');
  });
  test('Comparison accepts long pickup time and clears stale invalid results',()=>{
    const ui=createApp();ui.set('cps',12.3456);ui.set('css2',.5);ui.api.uCmp();near(Number(ui.get('cr-scs')),13.0816,.0001);
    ui.set('cq-scs',0);ui.api.uCmp();assert(ui.get('cr-scs')==='—'&&ui.get('cmp-verdict')==='','Stale comparison cleared');
    ui.set('cq-scs',24.75);ui.api.uCmp();assert(ui.get('cr-scs')!=='—','Valid values recover');
  });
  test('Container and fleet conservation across representative routes',()=>{
    for(let volume=1;volume<=300;volume+=7){
      const v=api.calcSCS({...scs,vd:volume});assert(v.fullTrips*v.CT+v.lastContainers===v.containers,'Containers conserved');
      assert(v.Nd*v.q>=volume&&Math.max(0,v.Nd-1)*v.q<volume,'Minimal discrete trip count');
      const h=api.calcHCS({...hcs,vd:volume});assert(h.fleet*h.capacity>=h.required,'Fleet meets demand');assert((h.fleet-1)*h.capacity<h.required,'Fleet minimal');
    }
  });
  return {total:results.length,passed:results.filter(r=>r.passed).length,failures:results.filter(r=>!r.passed)};
}
if(typeof require==='function'&&typeof module!=='undefined'&&require.main===module){
  const fs=require('node:fs'),path=require('node:path');
  const html=fs.readFileSync(path.join(__dirname,'..','index.html'),'utf8');
  const result=runTests(html, loadSource(__dirname));
  console.log(JSON.stringify(result,null,2));if(result.failures.length)process.exitCode=1;
}
