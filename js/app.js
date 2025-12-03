
// app.js - منطق الواجهة الرئيسية
const DB_KEY = 'islamic_platform_db_v1';
let db = { users: [], lessons: LESSONS };
let currentUser = null;
let activeLesson = null;
let timerInterval = null;
let timeLeft = 0;
function saveDB(){ localStorage.setItem(DB_KEY, JSON.stringify(db)); }
function loadDB(){ const s = localStorage.getItem(DB_KEY); if(s){ try{ db = JSON.parse(s); if(!db.lessons) db.lessons = LESSONS; }catch(e){ db = {users:[], lessons:LESSONS}; } } else { db = {users:[], lessons:LESSONS}; } }
function init(){
  loadDB();
  // create demo user if not exists
  if(!db.users.find(u=>u.username==='demo')) {
    db.users.push({username:'demo', password:'demo', name:'سعيد', section:'1', progress:{}, badges:[]});
    saveDB();
  }
  bindEvents();
}
function bindEvents(){
  document.getElementById('show-register').addEventListener('click', e=>{e.preventDefault(); toggleAuth('register');});
  document.getElementById('show-login').addEventListener('click', e=>{e.preventDefault(); toggleAuth('login');});
  document.getElementById('login-btn').addEventListener('click', login);
  document.getElementById('register-btn').addEventListener('click', register);
  document.getElementById('logout').addEventListener('click', ()=>{ currentUser=null; renderAuth();});
  document.getElementById('search-lesson').addEventListener('input', renderLessons);
  document.getElementById('back-to-dash').addEventListener('click', ()=>{ showSection('dashboard');});
  document.getElementById('start-quiz').addEventListener('click', startQuiz);
  document.getElementById('submit-quiz').addEventListener('click', submitQuiz);
  document.getElementById('themeToggle').addEventListener('click', toggleTheme);
  renderAuth();
}
function toggleAuth(type){
  document.getElementById('login').style.display = type==='login' ? 'block' : 'none';
  document.getElementById('register').style.display = type==='register' ? 'block' : 'none';
}
function renderAuth(){
  document.getElementById('auth').style.display = 'block';
  document.getElementById('dashboard').style.display = 'none';
  document.getElementById('lesson-view').style.display = 'none';
  document.getElementById('quiz-view').style.display = 'none';
}
function login(){
  const u = document.getElementById('login-username').value.trim();
  const p = document.getElementById('login-password').value;
  const user = db.users.find(x=>x.username===u && x.password===p);
  if(user){ currentUser = user; showDashboard(); } else alert('بيانات الدخول غير صحيحة');
}
function register(){
  const u = document.getElementById('reg-username').value.trim();
  if(!u) return alert('أدخل اسم مستخدم');
  if(db.users.find(x=>x.username===u)) return alert('اسم المستخدم موجود');
  const user = { username: u, password: document.getElementById('reg-password').value, name: document.getElementById('reg-name').value || u, section: document.getElementById('reg-section').value || '-', progress:{}, badges:[] };
  db.users.push(user); saveDB(); alert('تم إنشاء الحساب'); toggleAuth('login');
}
function showSection(sec){
  document.getElementById('auth').style.display = sec==='auth' ? 'block' : 'none';
  document.getElementById('dashboard').style.display = sec==='dashboard' ? 'block' : 'none';
  document.getElementById('lesson-view').style.display = sec==='lesson' ? 'block' : 'none';
  document.getElementById('quiz-view').style.display = sec==='quiz' ? 'block' : 'none';
  document.getElementById('badges-view').style.display = sec==='badges' ? 'block' : 'none';
}
function showDashboard(){
  document.getElementById('student-name').innerText = currentUser.name;
  document.getElementById('auth').style.display = 'none';
  renderLessons();
  showSection('dashboard');
}
function renderLessons(){
  const container = document.getElementById('lessons-grid');
  container.innerHTML = '';
  const q = document.getElementById('search-lesson').value.trim();
  db.lessons.filter(l=> l.title.includes(q) || l.content.includes(q)).forEach(l=>{
    const card = document.createElement('div');
    card.className='lesson-card';
    card.innerHTML = `<h3>${l.title}</h3><p>نتيجة النجاح: ${l.passScore}% · زمن الاختبار: ${Math.round(l.timeLimit/60)} د</p>`;
    card.onclick = ()=> openLesson(l.id);
    container.appendChild(card);
  });
}
function openLesson(id){
  activeLesson = db.lessons.find(x=>x.id===id);
  document.getElementById('lesson-title').innerText = activeLesson.title;
  document.getElementById('lesson-content').innerHTML = activeLesson.content;
  document.getElementById('timer').style.display = 'none';
  showSection('lesson');
}
function startQuiz(){
  if(!activeLesson) return;
  // render questions
  const area = document.getElementById('quiz-questions');
  area.innerHTML = '';
  activeLesson.questions.forEach((q,i)=>{
    const block = document.createElement('div');
    block.className='question-block';
    let html = `<p><strong>${i+1}. ${q.q}</strong></p>`;
    q.options.forEach((opt, idx)=>{
      html += `<label><input type="radio" name="q${i}" value="${idx}"> ${opt}</label><br/>`;
    });
    block.innerHTML = html;
    area.appendChild(block);
  });
  // timer
  timeLeft = activeLesson.timeLimit;
  document.getElementById('timer').style.display = 'inline-block';
  updateTimerText();
  if(timerInterval) clearInterval(timerInterval);
  timerInterval = setInterval(()=>{
    timeLeft--;
    updateTimerText();
    if(timeLeft<=0){ clearInterval(timerInterval); submitQuiz(); }
  },1000);
  document.getElementById('quiz-title').innerText = activeLesson.title;
  showSection('quiz');
}
function updateTimerText(){
  const mm = Math.floor(timeLeft/60).toString().padStart(2,'0');
  const ss = (timeLeft%60).toString().padStart(2,'0');
  document.getElementById('timer').innerText = mm+':'+ss;
}
function submitQuiz(){
  if(timerInterval) clearInterval(timerInterval);
  const answers = [];
  activeLesson.questions.forEach((q,i)=>{
    const sel = document.querySelector(`input[name="q${i}"]:checked`);
    answers.push(sel ? parseInt(sel.value) : null);
  });
  let score = 0;
  activeLesson.questions.forEach((q,i)=>{ if(answers[i]===q.answer) score++;});
  const pct = Math.round((score/activeLesson.questions.length)*100);
  // save progress
  currentUser.progress = currentUser.progress || {};
  currentUser.progress[activeLesson.id] = { score: pct, answers: answers, timeTaken: activeLesson.timeLimit - timeLeft, timeLimit: activeLesson.timeLimit, date: new Date().toISOString() };
  // award badges
  awardBadgesToStudent(currentUser, Object.assign({}, currentUser.progress[activeLesson.id]));
  // persist
  const idx = db.users.findIndex(u=>u.username===currentUser.username);
  if(idx>=0){ db.users[idx] = currentUser; saveDB(); }
  alert('تم حفظ النتيجة: ' + pct + '%');
  showDashboard();
}
function toggleTheme(){
  document.body.classList.toggle('dark');
  const en = document.body.classList.contains('dark');
  try{ document.getElementById('dark-css').disabled = !en; }catch(e){}
  localStorage.setItem('theme', en ? 'dark' : 'light');
}
window.addEventListener('load', ()=>{
  init();
  // restore theme
  if(localStorage.getItem('theme')==='dark'){ document.body.classList.add('dark'); try{document.getElementById('dark-css').disabled = false;}catch(e){} }
});
