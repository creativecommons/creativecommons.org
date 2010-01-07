from cc.engine import staticdirect


def test_remote_staticdirect():
    sd = staticdirect.RemoteStaticDirect('/statik')
    assert sd('/foo/') == '/statik/foo/'
    assert sd('/foo/bar/baz') == '/statik/foo/bar/baz'
    assert sd('blah') == '/statik/blah'


def test_multi_remote_staticdirect():
    sd = staticdirect.MultiRemoteStaticDirect(
        {'images': 'http://img.example.org/images/',
         'js': '/statik/js/',
         'css': '/statik/stylesheets/'})
    assert sd('/images/intarwebs.jpg') == \
        'http://img.example.org/images/intarwebs.jpg'
    assert sd('/js/joovascreept.js') == '/statik/js/joovascreept.js'
    assert sd('/css/stylish.css') == '/statik/stylesheets/stylish.css'


def test_request_dot_staticdirect():
    assert 0


def test_static_app_factory():
    assert 0
