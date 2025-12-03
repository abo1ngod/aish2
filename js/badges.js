
// badges.js - تعريف الشارات وطرق التتويج
const BADGES = [
  {id:'perfect', name:'درجة كاملة', icon:'assets/icons/perfect.svg', condition: s => s.score===100},
  {id:'fast', name:'منجز سريع', icon:'assets/icons/fast.svg', condition: s => s.timeTaken && s.timeTaken < (s.timeLimit/2)},
  {id:'consistent', name:'مثابر', icon:'assets/icons/streak.svg', condition: s => s.streak && s.streak >= 3}
];

function awardBadgesToStudent(student, lessonResult) {
  student.badges = student.badges || [];
  BADGES.forEach(b => {
    try{
      if(b.condition(lessonResult) && !student.badges.includes(b.id)) {
        student.badges.push(b.id);
      }
    }catch(e){}
  });
}
