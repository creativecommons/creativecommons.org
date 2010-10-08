from cc.engine import decorators
from webob import Request, Response, exc

def test_restrict_http_methods():
    # Prep the tester requests
    get_request = Request.blank('/')
    get_request.method = 'GET'
    post_request = Request.blank('/')
    post_request.method = 'POST'
    put_request = Request.blank('/')
    put_request.method = 'PUT'

    # Test view for one allowed method only
    # -------------------------------------
    @decorators.RestrictHttpMethods('GET')
    def restricted_view(request):
        return Response('successful response')

    # pass
    response = restricted_view(get_request)
    assert response.status_int == 200
    assert response.body == 'successful response'

    # fails
    response = restricted_view(post_request)
    assert response.status_int == 405

    # Test view for multiple methods allowed
    # --------------------------------------
    @decorators.RestrictHttpMethods('POST', 'PUT')
    def restricted_view(request):
        return Response('successful response')

    # pass
    response = restricted_view(post_request)
    assert response.status_int == 200
    assert response.body == 'successful response'

    response = restricted_view(put_request)
    assert response.status_int == 200
    assert response.body == 'successful response'

    # fails
    response = restricted_view(get_request)
    assert response.status_int == 405
