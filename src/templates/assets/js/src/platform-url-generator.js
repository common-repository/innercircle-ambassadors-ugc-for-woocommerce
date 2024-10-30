class IcPlatformUrlGenerator {
    rid;
    cid;
    pid;

    constructor(){
        this.fetchUrlParams()
        this.generateUrlParamsForIframe()
    }

    fetchUrlParams() {
        this.rid = this.getUrlParameter('rid')
        this.cid = this.getUrlParameter('cid')
        this.pid = this.getUrlParameter('pid')
    }

    generateUrlParamsForIframe() {
        let url = `${INNERCIRCLE_PORTAL_URL}`
        if(IC_COMPANY_SLUG)
            url = `https://${IC_COMPANY_SLUG}.${INNERCIRCLE_HOST}/${IC_COMPANY_SLUG}`

        url += `/login?`
        if(this.rid)
            url += `rid=${this.rid}`
        else if(this.pid)
            url += `pid=${this.pid}`

        if(!this.rid && !this.pid && this.cid)
            url += `cid=${this.cid}`
        else if((this.rid || this.pid) && this.cid)
            url += `&cid=${this.cid}`
        this.redirectToInnercircle(url);
    }

    redirectToInnercircle(url) {
        window.location.href = url;
    }

    getUrlParameter(sParam) {
        let sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] === sParam) {
                return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };
}
export default IcPlatformUrlGenerator;
