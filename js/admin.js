
/* admin.js - أدوات المعلم */
const ADMIN_PIN = '1234';
function renderStudentsTable(){
  const tbody = document.querySelector('#students-table tbody');
  tbody.innerHTML = '';
  const dbs = JSON.parse(localStorage.getItem('islamic_platform_db_v1') || '{}');
  const users = (dbs.users || []);
  users.forEach(u=>{
    const progCount = u.progress ? Object.keys(u.progress).length : 0;
    const pct = Math.round((progCount / (dbs.lessons?dbs.lessons.length:1)) * 100);
    const scores = u.progress ? Object.keys(u.progress).map(k=> k+':'+u.progress[k].score+'%').join(', ') : '';
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${u.name}</td><td>${u.section}</td><td>${pct}%</td><td>${scores}</td>`;
    tbody.appendChild(tr);
  });
}
function bindAdmin(){
  document.getElementById('export-csv').addEventListener('click', ()=> {
    const dbs = JSON.parse(localStorage.getItem('islamic_platform_db_v1') || '{}');
    let csv = '\uFEFFالاسم,الشعبة,الدرس,الدرجة,تاريخ\n';
    (dbs.users||[]).forEach(u=>{
      if(u.progress){
        Object.keys(u.progress).forEach(k=>{
          const r = u.progress[k];
          csv += `"${u.name}","${u.section}","${k}","${r.score}","${r.date || ''}"\n`;
        });
      } else {
        csv += `"${u.name}","${u.section}","","",""\n`;
      }
    });
    const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a'); a.href=url; a.download='grades.csv'; a.click();
  });
  document.getElementById('export-pdf').addEventListener('click', ()=> {
    const dbs = JSON.parse(localStorage.getItem('islamic_platform_db_v1') || '{}');
    const { jsPDF } = window.jspdf || {};
    const doc = new (jsPDF || function(){} )();
    doc.text('تقارير الطلاب', 10, 10);
    let y = 20;
    (dbs.users||[]).forEach(u=>{
      doc.text(`${u.name} - ${u.section}`, 10, y); y+=6;
    });
    try{ doc.save('report.pdf'); }catch(e){ alert('PDF not available in offline mode'); }
  });
  document.getElementById('search-students').addEventListener('input', ()=>{
    const q = document.getElementById('search-students').value.trim().toLowerCase();
    // simple filter by name
    const rows = document.querySelectorAll('#students-table tbody tr');
    rows.forEach(r=>{
      r.style.display = r.children[0].innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
  document.getElementById('reset-lessons').addEventListener('click', ()=>{
    if(confirm('استعادة الدروس الافتراضية؟')) {
      localStorage.removeItem('islamic_platform_db_v1'); location.reload();
    }
  });
  document.getElementById('add-lesson').addEventListener('click', ()=>{
    const title = prompt('عنوان الدرس:');
    if(!title) return;
    const newLesson = { id: Date.now(), title: title, content: prompt('محتوى الدرس (HTML):') || '', questions: [], passScore:50, timeLimit:180 };
    const dbs = JSON.parse(localStorage.getItem('islamic_platform_db_v1') || '{}');
    if(!dbs.lessons) dbs.lessons = [];
    dbs.lessons.push(newLesson);
    localStorage.setItem('islamic_platform_db_v1', JSON.stringify(dbs));
    renderStudentsTable();
    alert('تم إضافة درس جديد - أعد تحميل الصفحة لظهوره');
  });
}
// init admin
window.addEventListener('load', ()=>{
  if(!localStorage.getItem('islamic_platform_db_v1')) {
    // copy initial data if missing
    const initial = { users:[{username:'demo',password:'demo',name:'سعيد',section:'1',progress:{}}], lessons:LESSONS };
    localStorage.setItem('islamic_platform_db_v1', JSON.stringify(initial));
  }
  renderStudentsTable();
  bindAdmin();
  document.getElementById('themeToggleAdmin').addEventListener('click', ()=>{
    document.body.classList.toggle('dark');
    try{ document.getElementById('dark-css-admin').disabled = !document.body.classList.contains('dark'); }catch(e){}
  });
});
