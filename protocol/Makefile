DOKUMENT = protokoll
DOCUMENT = protocol

all: en

clean: 
	rm -f $(DOKUMENT).tex~
	rm -f $(DOKUMENT).aux
	rm -f $(DOKUMENT).log
	rm -f $(DOKUMENT).out
	rm -f $(DOKUMENT).toc
	rm -f $(DOKUMENT).pdflatex.output
	rm -f $(DOCUMENT).tex~
	rm -f $(DOCUMENT).aux
	rm -f $(DOCUMENT).log
	rm -f $(DOCUMENT).out
	rm -f $(DOCUMENT).toc
	rm -f $(DOCUMENT).pdflatex.output

clean-all: clean
	rm -f $(DOKUMENT).pdf
	rm -f $(DOCUMENT).pdf

de: $(DOKUMENT).pdf

en: $(DOCUMENT).pdf

$(DOKUMENT).pdf: $(DOKUMENT).tex
	pdflatex $(DOKUMENT).tex | tee $(DOKUMENT).pdflatex.output
	grep "Rerun to get cross-references right." $(DOKUMENT).pdflatex.output && pdflatex $(DOKUMENT).tex
	rm -f $(DOKUMENT).pdflatex.output

$(DOCUMENT).pdf: $(DOCUMENT).tex
	pdflatex $(DOCUMENT).tex | tee $(DOCUMENT).pdflatex.output
	grep "Rerun to get cross-references right." $(DOCUMENT).pdflatex.output && pdflatex $(DOCUMENT).tex
	rm -f $(DOCUMENT).pdflatex.output

# end
