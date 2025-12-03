# منصة الدراسات الإسلامية - نسخة مطورة

هذه الحزمة تحتوي على مشروع ويب ثابت (واجهة طالب + لوحة معلم) مع:
- دروس كاملة (25 درس) داخل `js/lessons.js`
- نظام اختبارات مؤقت (Timer)
- نظام شارات
- تصدير CSV و PDF من لوحة المعلم
- Dark mode

## ما الذي فعلته لك هنا
1. ملأت `js/lessons.js` بمحتوى الدروس الـ25 باللغة العربية كما طلبت.
2. أضفت ملفات مساعدة لنشر المشروع على Firebase Hosting (انظر firebase.json).
3. جهزت README يوضح خطوات رفع المشروع وتهيئته محليًا ونشره.

## نشر إلى Firebase Hosting (ملفّياً)
1. ثبت Firebase CLI: https://firebase.google.com/docs/cli
2. سجل دخولك: `firebase login`
3. أنشئ مشروع Firebase في Console ثم نفّذ محليًا: `firebase init hosting`
   - اختر مجلد النشر `public` أو اجعل `public` يشير إلى جذر المشروع.
4. انسخ ملفات المشروع إلى مجلد `public` أو حدّد المسارات المناسبة.
5. انشر: `firebase deploy --only hosting`

> ملاحظة: لن يتم إنشاء قاعدة بيانات Firestore تلقائيًا من هذه الحزمة — ستحتاج لإعداد Firestore أو Realtime Database في Console إن رغبت بتخزين بيانات المستخدمين في السحابة.

## رفع إلى GitHub
1. أنشئ مستودع جديد على GitHub
2. على جهازك المحلي:
   ```bash
   git init
   git add .
   git commit -m "Initial commit - Islamic platform"
   git branch -M main
   git remote add origin https://github.com/YOURUSERNAME/REPO.git
   git push -u origin main
   ```
3. إن أردت أستطيع مساعدتك بصيغة ملفات جاهزة بفرع رئيسي ومُهيّأ.

---
