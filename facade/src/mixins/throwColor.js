export default {
    methods: {
        throwColor(type, targetElement, targetElementColor, targetReplaceElementColor = 'text-negative') {
            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.attributeName === 'class' && document.querySelector(targetElement).parentElement.parentElement.classList.contains(targetReplaceElementColor)) {
                        var inputElementClassList = document.querySelector(targetElement).parentElement.parentElement.classList;
                        inputElementClassList.remove(targetReplaceElementColor);
                        inputElementClassList.add(targetElementColor);

                        var childrenElement = document.querySelector(targetElement).parentElement.parentElement.childNodes;
                        childrenElement.forEach( function(htmlTag) {
                            console.log();
                            if(htmlTag.firstChild.tagName == 'I') {
                                var elementClassList = htmlTag.firstChild.classList;
                                elementClassList.add(targetElementColor);
                            }
                        });

                        var inputElementMessageClassList = document.querySelector(targetElement).parentElement.parentElement.parentElement.lastChild.classList;
                        inputElementMessageClassList.add(targetElementColor);
                    }
                    if (mutation.attributeName === 'class' && document.querySelector(targetElement).parentElement.parentElement.parentElement.classList.contains(targetReplaceElementColor)) {
                        var inputElementClassList = document.querySelector(targetElement).parentElement.parentElement.parentElement.classList;
                        inputElementClassList.remove(targetReplaceElementColor);
                        inputElementClassList.add(targetElementColor);


                        var childrenElement = document.querySelector(targetElement).parentElement.parentElement.parentElement.childNodes;
                        childrenElement.forEach( function(htmlTag) {
                            if(htmlTag.firstChild) {
                                if(htmlTag.firstChild.tagName == 'I') {
                                    var elementClassList = htmlTag.firstChild.classList;
                                    elementClassList.add(targetElementColor);
                                }
                            }

                        });

                        var inputElementMessageClassList = document.querySelector(targetElement).parentElement.parentElement.parentElement.nextSibling.firstChild.firstChild.classList;
                        inputElementMessageClassList.add(targetElementColor);
                    }
                });
            });

            // for input & textarea
            if (type == 'input') {
                if (document.querySelector(targetElement) != null) {
                    const element = document.querySelector(targetElement).parentElement.parentElement;
                    observer.observe(element, { childList: false, attributes: true });
                }
            }
            
            // for select only
            if (type == 'select') {
                if (document.querySelector(targetElement) != null) {
                    const element = document.querySelector(targetElement).parentElement.parentElement.parentElement;
                    observer.observe(element, { childList: false, attributes: true });
                }
            }
        },

        // Examples
        // this.throwColor("input", "input.name", "text-amber");
        // this.throwColor("select", "input.order", "text-amber");
        // this.throwColor("input", "textarea.namearea", "text-amber");
    }
};