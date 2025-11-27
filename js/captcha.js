document.addEventListener('DOMContentLoaded', () => {
    function setupCaptchaForModal(modalId, questionId, aId, bId, answerId) {
        const modalEl      = document.getElementById(modalId);
        const questionEl   = document.getElementById(questionId);
        const aField       = document.getElementById(aId);
        const bField       = document.getElementById(bId);
        const answerField  = document.getElementById(answerId);

        if (!modalEl || !questionEl || !aField || !bField || !answerField) return;

        modalEl.addEventListener('show.bs.modal', () => {
            const a = Math.floor(Math.random() * 10) + 1; // 1–10
            const b = Math.floor(Math.random() * 10) + 1; // 1–10

            questionEl.textContent = `Prove you're human: what is ${a} + ${b}?`;
            aField.value = a;
            bField.value = b;
            answerField.value = '';
        });
    }

    setupCaptchaForModal('addMovieModal',  'addCaptchaQuestion',  'addCaptchaA',  'addCaptchaB',  'addCaptchaAnswer');
    setupCaptchaForModal('editMovieModal', 'editCaptchaQuestion', 'editCaptchaA', 'editCaptchaB', 'editCaptchaAnswer');
});
